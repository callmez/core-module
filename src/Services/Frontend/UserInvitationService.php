<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\src\Events\Frontend\UserInvited;
use Modules\Core\src\Models\Frontend\UserInvitation;
use Modules\Core\src\Services\Traits\HasQueryOptions;
use UnexpectedValueException;


class UserInvitationService
{
    use HasQueryOptions;

    /**
     * @param $where
     * @param array $options
     *
     * @return UserInvitation
     * @throws ModelNotFoundException
     */
    public function getUserInvitation($where, array $options = [])
    {
        $invitation = $this->withQueryOptions(UserInvitation::where($where), $options)->first();

        if ( ! $invitation && ($options['exception'] ?? true)) {
            throw new ModelNotFoundException(trans('邀请码未找到'));
        }

        return $invitation;
    }

    /**
     * @param $token
     * @param array $options
     *
     * @return UserInvitation
     */
    public function getUserInvitationByToken($token, array $options = [])
    {
        $available = $options['available'] ?? true;

        $invitation = $this->getUserInvitation(['token' => $token], array_merge([
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
            $invitation = $this->getUserInvitationByToken($token, ['exception' => false]);

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
     * @return UserInvitation
     */
    public function create($user, $token = null, $expiredAt = null, array $options = [])
    {
        return $this->createByData([
            'user_id' => with_user_id($user),
            'token' => $token,
            'expired_at' => $expiredAt
        ], $options);
    }

    /**
     * @param array $data
     * @param $options
     *
     * @return UserInvitation
     */
    public function createByData(array $data, array $options = [])
    {
        /** @var UserInvitation $invitation */
        $invitation = UserInvitation::create(array_merge($data, [
            'token'      => $data['token'] ?: $this->generateUniqueToken(),
            'expired_at' => $data['expired_at'] ?: Carbon::now()->addSeconds(config('core::user.invitation.expires', 600)),
        ]));

        return $invitation;
    }

    /**
     * @param $token
     * @param User $user
     *
     * @return UserInvitation
     */
    public function inviteOneUser($token, User $usedUser)
    {
        $invitation = $this->getUserInvitationByToken($token);

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
    public function inviteAnyUser($token, User $usedUser)
    {
        $invitation = $this->getUserInvitationByToken($token);

        $usedInvitation = $invitation->replicate();
        $usedInvitation->setUsed($usedUser);

        if (!$usedInvitation->save()) {
            throw ModelSaveException::withModel($usedInvitation);
        }

        event(new UserInvited($usedInvitation));

        return $usedInvitation;
    }

}
