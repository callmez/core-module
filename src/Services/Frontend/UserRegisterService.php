<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Core\Events\Frontend\UserRegistered;

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
            /** @var UserService $userService */
            $userService = resolve(UserService::class);

            /** @var User $user */
            $user = $userService->create([
                'username' => $data['username'],
                'password' => $data['password'],
                'mobile' => $data['mobile'] ?? '',
                'email' => $data['email'] ?? ''
            ], $options['createOptions'] ?? []);

            /** @var UserInvitationService $invitationService */
            $invitationService = resolve(UserInvitationService::class);
            $invitationService->inviteUser($data['invite_code'] ?? null, $user, array_merge([
                'invitation' => config('core::system.register.invitation', 1)
            ], $options['inviteOptions'] ?? []));

            return $user;
        });

        event(new UserRegistered($user));

        $user->refresh();

        return $user;
    }
}
