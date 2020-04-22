<?php

namespace Modules\Core\Services\Frontend;

use Cache;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Core\Events\Frontend\UserLoggedIn;
use Modules\Core\Events\Frontend\UserRegistered;
use Modules\Core\src\Services\Traits\HasQueryOptions;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyException;
use Modules\Core\src\Exceptions\Frontend\Auth\UserNotFoundException;
use Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException;

class UserRegisterService
{

    /**
     * 用户注册
     *
     * @param array $data
     * @param array $options
     *
     * @return User
     */
    public function register(array $data, array $options = [])
    {
        /** @var User $user */
        $user = DB::transaction(function() use ($data, $options) {
            /** @var User $user */
            $user = User::create([
                'username' => $data['username'],
                'password' => $data['password'],
                'mobile' => $data['mobile'] ?? '',
                'email' => $data['email'] ?? ''
            ]);

            $this->processInvitation($data, $user);

            return $user;
        });

        event(new UserRegistered($user));

        $user->refresh();

        return $user;
    }

    protected function processInvitation(array $data, User $usedUser)
    {
        $invitationState = config('core::system.register.invitation', 2);
        if ($invitationState == 0) { // 不开启邀请码
            return ;
        }

        $token = $data['invite_code'] ?? null;
        /** @var UserInvitationService $invitationService */
        $invitationService = resolve(UserInvitationService::class);

        if ($invitationState == 1) { // 一码一人模式
            $invitation = $invitationService->inviteOneUser($token, $usedUser);
        } else { // 一码多人模式
            $invitation = $invitationService->inviteAnyUser($token, $usedUser);
        }

        return $invitation;
    }
}
