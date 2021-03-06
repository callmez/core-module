<?php

namespace Modules\Core\Services\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Frontend\UserVerify;
use PascalDeVink\ShortUuid\ShortUuid;
use Modules\Core\Services\Traits\HasThrottles;
use Modules\Core\Events\Frontend\UserRegistered;
use Modules\Core\Notifications\Frontend\UserMobileVerify;

class UserRegisterService
{
    use HasThrottles;



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
                'invitation' => config('core::system.register.invitation', 0)
            ], $options['inviteOptions'] ?? []));

            return $user;
        });

        event(new UserRegistered($user));

        return $user;
    }

    /**
     * 用户注册(手机号方式)
     *
     * @param array $data
     * @param array $options
     *
     * @return User
     */
    public function registerByMobile(array $data, array $options = [])
    {
        /** @var User $user */
        $user = DB::transaction(function() use ($data, $options) {
            /** @var UserVerifyService $userService */
            $userService = resolve(UserVerifyService::class);
            $userService->getByKeyToken($data['mobile'], $data['code'], UserVerify::TYPE_MOBILE_REGISTER, $options['userVerifyOptions'] ?? []);

            /** @var UserService $userService */
            $userService = resolve(UserService::class);
            /** @var User $user */
            $user = $userService->create([
                'username' => $data['username'] ?? ShortUuid::uuid1(),
                'password' => $data['password'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? ''
            ], array_merge([
                'beforeSave' => function($model) {
                    /** @var User $model */
                    $model->setMobileVerified($model->mobile); // 标记为邮箱已验证
                }
            ], $options['createOptions'] ?? []));

            /** @var UserInvitationService $invitationService */
            $invitationService = resolve(UserInvitationService::class);
            $invitationService->inviteUser($data['invite_code'] ?? null, $user, array_merge([
                'invitation' => config('core::system.register.invitation', 0)
            ], $options['inviteOptions'] ?? []));

            return $user;
        });

        event(new UserRegistered($user));

        return $user;
    }
}
