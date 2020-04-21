<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Closure;
use Dotenv\Exception\ValidationException;
use Modules\Core\Exceptions\ModelSaveException;
use UnexpectedValueException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\src\Models\Auth\UserInvitation;
use Modules\Core\src\Services\Traits\HasQueryOptions;


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
     * @throws ModelNotFoundException
     */
    public function getUserInvitationByToken($token, array $options = [])
    {
        return $this->getUserInvitation(['token' => $token], $options);
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
        $user = with_user($user);

        /** @var UserInvitation $invitation */
        $invitation = $user->invitations()->create([
            'user_id'    => $user->id,
            'token'       => $token ?: $this->generateUniqueToken(),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.invitation.expires', 600)),
        ]);

        return $invitation;
    }

    /**
     * @param $token
     * @param User $user
     *
     * @return UserInvitation
     */
    public function toOneUser($token, User $usedUser)
    {
        $invitation = $this->getUserInvitationByToken($token);

        if ($invitation->isExpired()) {
            throw ValidationException::withMessages([
                'mobile' => [trans('邀请码已过期')],
            ]);
        } elseif ($invitation->isUsed()) {
            throw ValidationException::withMessages([
                'mobile' => [trans('邀请码已经被使用')],
            ]);
        }

        $invitation->setUsed($usedUser);

        if (!$invitation->save()) {
            throw ModelSaveException::withModel($invitation);
        }

        return $invitation;
    }

    /**
     * 一码多人模式
     *
     * @param $token
     * @param Closure $userResolver
     */
    public function toAnyUser($token, User $usedUser)
    {
        $invitation = $this->getUserInvitationByToken($token);

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

}
