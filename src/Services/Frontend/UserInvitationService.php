<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Events\Frontend\UserInvited;
use Modules\Core\Models\Frontend\UserInvitation;

class UserInvitationService
{
    use HasQuery {
        one as queryOne;
        getById as queryGetById;
        create as queryCreate;
    }
    /**
     * @var UserInvitation
     */
    protected $model;

    public function __construct(UserInvitation $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Closure|array|null $where
     * @param array $options
     *
     * @return mixed
     */
    public function one($where = null, array $options = [])
    {
        return $this->queryOne($where, array_merge([
            'exception' => function() {
                return new ModelNotFoundException(trans('邀请码未找到'));
            }
        ], $options));
    }

    /**
     * @param $token
     * @param array $options
     *
     * @return UserInvitation
     */
    public function getByToken($token, array $options = [])
    {
        $available = $options['available'] ?? false;

        $invitation = $this->one(['token' => $token], array_merge([
            'orderBy' => 'created_at',
        ], $options));

        if ($available) {
            if ($invitation->isExpired()) {
                throw ValidationException::withMessages([
                    'mobile' => [trans('邀请码已过期')],
                ]);
            } elseif ($invitation->isUsed()) {
                throw ValidationException::withMessages([
                    'mobile' => [trans('邀请码已经被使用')],
                ]);
            }
        }

        return $invitation;
    }

    /**
     * @param Closure|null $tokenCallback
     * @param array $otpions
     *
     * @return mixed|string
     * @throws ModelNotFoundException
     */
    public function generateUniqueToken(Closure $tokenCallback = null, array $options = [])
    {
        $i = 1;
        $max = $options['max'] ?? 10;
        while (true) {
            $token = is_callable($tokenCallback) ? $tokenCallback() : Str::random(6);
            $invitation = $this->getByToken($token, ['exception' => false]);

            if ( ! $invitation) {
                return $token;
            } elseif ($i > $max) {
                throw new UnexpectedValueException('Max generate user invitation token times.');
            }

            $i++;
        }
    }

    /**
     * @param $user
     * @param null $token
     * @param null $expiredAt
     * @param array $options
     *
     * @return bool|UserInvitation
     */
    public function createWithUser($user, $token = null, $expiredAt = null, array $options = [])
    {
        return $this->create([
            'user_id' => with_user_id($user),
            'token' => $token === null ? $this->generateUniqueToken() : $token,
            'expired_at' => $expiredAt
        ], $options);
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return bool|UserInvitation
     */
    public function create(array $data, array $options = [])
    {
        return $this->queryCreate(array_merge($data, [
            'token'      => $data['token'] ?: $this->generateUniqueToken(),
            'expired_at' => $data['expired_at'] ?: Carbon::now()->addSeconds(config('core::user.invitation.expires', 600)),
        ]), $options);
    }

    /**
     * 获取用户邀请人的上级邀请树用户
     *
     * @param User|int $user
     * @param array $options 上级邀请代数, 比如: 2=只返回2代邀请用户数据
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInvitersByUser($user, array $options = [])
    {
        $user = with_user($user);

        $data = $user->invitationTree ? $user->invitationTree->data : [];

        $level = $options['level'] ?? false;

        if ($level > 0) { // 取出指定代数数据
            $data = array_slice($data, 0, $level);
        }

        /** @var UserService $userService */
        $userService = resolve(UserService::class);

        return $userService->all(function($query) use ($data) {
            $query->whereIn('id', $data);
        }, $options['allOptions'] ?? [])->keyBy('id');
    }

    /**
     * @param array $data
     * @param User $usedUser
     *
     * @return UserInvitation
     */
    public function inviteUser($token, User $usedUser, array $options = [])
    {
        $invitationState = $options['invitation'] ?? 1;

        if ($invitationState == 0) { // 不开启邀请码
            return ;
        }

        if ($invitationState == 1) { // 一码一人模式
            $invitation = $this->inviteOneUser($token, $usedUser);
        } else { // 一码多人模式
            $invitation = $this->inviteAnyUser($token, $usedUser);
        }

        return $invitation;
    }

    /**
     * 一码多人模式
     *
     * @param $token
     * @param $user
     *
     * @return UserInvitation
     */
    protected function inviteOneUser($token, $usedUser)
    {
        $usedUser = with_user($usedUser);

        $invitation = $this->getByToken($token, ['available' => true]);

        $invitation->setUsed($usedUser);

        if (!$invitation->save()) {
            throw ModelSaveException::withModel($invitation);
        }

        event(new UserInvited($invitation));

        return $invitation;
    }

    /**
     * 一码多人模式
     *
     * @param $token
     * @param Closure $userResolver
     */
    protected function inviteAnyUser($token, $usedUser)
    {
        $usedUser = with_user($usedUser);

        $invitation = $this->getByToken($token, ['available' => true]);

        $usedInvitation = $invitation->replicate();
        $usedInvitation->setUsed($usedUser);

        if (!$usedInvitation->save()) {
            throw ModelSaveException::withModel($usedInvitation);
        }

        event(new UserInvited($usedInvitation));

        return $usedInvitation;
    }

}
