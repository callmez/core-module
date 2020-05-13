<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use Modules\Core\Exceptions\Frontend\Auth\UserVerifyNotFundException;
use phpDocumentor\Reflection\Types\Self_;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Services\Traits\HasQuery;
use Modules\Core\Services\Traits\HasThrottles;

class UserVerifyService
{
    const TYPE_RESET_PASSWORD = 'reset_password';
    const TYPE_CHANGE_PASSWORD = 'change_password';
    const TYPE_RESET_PAY_PASSWORD = 'reset_pay_pasword';
    const TYPE_CHANGE_PAY_PASSWORD = 'change_pay_password';
    const TYPE_RESET_EMAIL = 'reset_email';
    const TYPE_RESET_MOBILE = 'reset_mobile';
    const TYPE_SET_MOBILE = 'set_mobile';
    use HasQuery,
        HasThrottles;

    /**
     * @var UserVerify
     */
    protected $model;

    public function __construct(UserVerify $model)
    {
        $this->model = $model;
    }

    /**
     * @param $key
     * @param Closure|null $tokenCallback
     * @param array $options
     *
     * @return mixed|string
     */
    public function generateUniqueToken($key, Closure $tokenCallback = null, array $options = [])
    {
        $i = 1;
        $max = $options['max'] ?? 10;
        while (true) {
            $token = is_callable($tokenCallback) ? $tokenCallback() : Str::random(6);
            $verify = $this->one([
                'key' => $key,
                'token' => $token
            ], ['exception' => false]);

            if (!$verify) {
                return $token;
            } elseif ($i > $max) {
                throw new UnexpectedValueException('Max generate user verify token times.');
            }

            $i++;
        }
    }

    /**
     * @param $user
     * @param $key
     * @param $type
     * @param null $token
     * @param null $expiredAt
     * @param array $options
     *
     * @return UserVerify
     */
    public function createByUser($user, $key, $type, $token = null, $expiredAt = null, array $options = [])
    {
        $user = with_user($user);

        /** @var UserVerify $verify */
        $verify = $this->create([
            'user_id' => $user->id,
            'key' => $key,
            'type' => $type,
            'token' => $token ?: $this->generateUniqueToken($key),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.verify.expires', 600)),
        ], $options);

        $deleteOther = $options['delete_other'] ?? true;
        if ($deleteOther || ($options['expire_other'] ?? true)) {
            $verify->makeOtherExpired($deleteOther);
        }

        return $verify;
    }


    /**
     * @param $user
     * @param null $email
     * @param array $options
     *
     * @return bool
     */
    public function resetEmailNotification($user, $email = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $email = $email ?: $user->email;

        if (empty($email)) {
            ValidationException::withMessages([
                'mobile' => 'Email must be set.'
            ]);
        }

        if ($email == $user->$email && $user->isEmailVerified()) {
            ValidationException::withMessages([
                'mobile' => 'Current email is already verified.'
            ]);
        }

        $this->checkAttempts($user, self::TYPE_SET_MOBILE, self::TYPE_RESET_MOBILE);

        /** @var UserVerifyService $userVerifyService */
        $userVerifyService = resolve(UserVerifyService::class);

        /** @var UserVerify $verify */
        $verify = $userVerifyService->createByUser($user, $email, self::TYPE_RESET_MOBILE, null, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendEmailVerifyNotification($verify);

        return true;
    }

    /**
     * @param $token
     * @param $email
     * @param array $options
     *
     * @return bool
     */
    public function resetEmail($token, $email, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $email,
            'token' => $token,
            'type' => self::TYPE_RESET_EMAIL
        ], [
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException('Code verify fail');
            }
        ]);

        $userVerify->user->email = $email;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }


    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     *
     * @return bool
     */
    public function resetMobileNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $mobile = $mobile ?: $user->mobile;

        if (empty($mobile)) {
            ValidationException::withMessages([
                'mobile' => 'Mobile must be set.'
            ]);
        }

        if ($mobile == $user->mobile && $user->isMobileVerified()) {
            ValidationException::withMessages([
                'mobile' => 'Current mobile is already verified.'
            ]);
        }

        $this->checkAttempts($user, self::TYPE_RESET_MOBILE);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_RESET_MOBILE, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    /**
     * @param $token
     * @param $mobile
     * @param array $options
     *
     * @return bool
     * @throws UserVerifyNotFoundException
     */
    public function resetMobile($token, $mobile, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_MOBILE
        ], [
            'with' => ['user'],
            'exception' => function () {
                return new UserVerifyNotFundException('Sms verify fail');
            }
        ]);

        $userVerify->user->mobile = $mobile;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }


    /**
     * @param null $mobile
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function resetPasswordNotification($mobile = null, array $options = [])
    {
        $userService = resolve(UserService::class);

        /** @var User $user */
        $user = $userService->one(['mobile' => $mobile]);

        if (!$user->isMobileVerified()) {
            throw ValidationException::withMessages([
                'mobile' => 'Mobile not verified.'
            ]);
        }

        $this->checkAttempts($user, self::TYPE_RESET_PASSWORD);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_RESET_PASSWORD, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    /**通过短信重置密码
     * @param $token
     * @param $mobile
     * @param array $options
     * @return bool
     * @throws ModelSaveException
     */
    public function resetPassword($token, $mobile, $password, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_PASSWORD
        ], array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->password = $password;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }
        $userVerify->setExpired()->save();
        return true;
    }


    /**
     * @param $user
     * @param $data
     * @param array $options
     */
    public function changePassword($user, $oldPassword, $newPassword, array $options = [])
    {
        $user = with_user($user);
        $userService = resolve(UserService::class);
        $userService->checkPassword($user, $oldPassword, $options);
        $user->password = $newPassword;
        if (!$user->save()) {
            throw ModelSaveException::withModel($user);
        }
        return true;
    }


    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function resetPayPasswordNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);

        $mobile = $mobile ?: $user->mobile;
        if (empty($mobile)) {
            throw ValidationException::withMessages([
                'mobile' => 'Mobile must be set.'
            ]);
        }

        if (!$user->isMobileVerified()) {
            throw ValidationException::withMessages([
                'mobile' => 'Mobile not verified.'
            ]);
        }

        $this->checkAttempts($user, self::TYPE_RESET_PAY_PASSWORD);

        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_RESET_PAY_PASSWORD, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    /**
     * @param $user
     * @param $token
     * @param $password
     * @param array $options
     * @return bool
     * @throws ModelSaveException
     */
    public function resetPayPassword($user, $token, $password, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $user->mobile,
            'token' => $token,
            'type' => self::TYPE_RESET_PAY_PASSWORD
        ], array_merge([
            'with' => ['user'],
        ], $options));

        $userVerify->user->pay_password = $password;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }
        $userVerify->setExpired()->save();

        return true;
    }


    /**
     * @param $user
     * @param $oldPassword
     * @param $newPassword
     * @param array $options
     * @return bool
     * @throws ModelSaveException
     * @throws \Modules\Core\Exceptions\Frontend\Auth\UserPayPasswordCheckException
     */
    public function changePayPassword($user, $oldPassword, $newPassword, array $options = [])
    {
        $user = with_user($user);
        $userService = resolve(UserService::class);
        $userService->checkPayPassword($user, $oldPassword, $options);
        $user->pay_password = $newPassword;
        if (!$user->save()) {
            throw ModelSaveException::withModel($user);
        }
        return true;
    }


    /**
     * @param $user
     * @param null $mobile
     * @param array $options
     * @return bool
     * @throws ValidationException
     */
    public function setMobileNotification($user, $mobile = null, array $options = [])
    {
        /** @var User $user */
        $user = with_user($user);
        if ($user->isMobileVerified()) {
            ValidationException::withMessages([
                'mobile' => 'User mobile is already verified.'
            ]);
        }

        $this->checkAttempts($user, self::TYPE_SET_MOBILE);
        $token = $this->generateUniqueToken($mobile, function () {
            return random_int(100000, 999999);
        });
        $verify = $this->createByUser($user, $mobile, self::TYPE_SET_MOBILE, $token, $options['createOptions'] ?? []);
        $verify->makeOtherExpired();

        $user->sendMobileVerifyNotification($verify);

        return true;
    }

    public function setMobile($user, $mobile, $token, array $options = [])
    {
        $userVerify = $this->one([
            'key' => $mobile,
            'token' => $token,
            'user_id' => $user->id,
            'type' => self::TYPE_SET_MOBILE
        ], array_merge([
            'with' => ['user'],
        ], $options));

        $user->setMobileVerified($mobile);
        if (!$user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }
        $userVerify->setExpired()->save();

        return true;
    }


    protected function checkAttempts($user, $type)
    {
        $key = with_user_id($user) . '|' . $type;
        $maxAttempts = $this->getMaxAttempts($type);
        $decaySeconds = $this->getDecaySecond($type);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'mobile' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
    }


    protected function getMaxAttempts($type)
    {
        return config('core:system.' . $type . '.maxAttempts', 3);
    }

    protected function getDecaySecond($type)
    {
        return config('core:system.' . $type . '.decaySeconds', 600);
    }
}
