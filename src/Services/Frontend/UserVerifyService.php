<?php

namespace Modules\Core\Services\Frontend;

use Closure;
use UnexpectedValueException;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Exceptions\ModelSaveException;
use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\src\Services\Traits\HasThrottles;
use Modules\Core\src\Services\Traits\HasQueryOptions;

class UserVerifyService
{
    use HasQueryOptions,
        HasThrottles;

    /**
     * @param $where
     * @param array $options
     *
     * @return UserVerify
     * @throws ModelNotFoundException
     */
    public function getUserVerify($where, array $options = [])
    {
        $verify = $this->withQueryOptions(UserVerify::where($where), $options)->first();

        if ( ! $verify && ($options['exception'] ?? true)) {
            throw new ModelNotFoundException('Verify token not found');
        }

        return $verify;
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
            $verify = $this->getUserVerify([
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
    public function create($user, $key, $type, $token = null, $expiredAt = null, array $options = [])
    {
        $user = with_user($user);

        /** @var UserVerify $verify */
        $verify = $user->verifies()->create([
            'user_id'    => $user->id,
            'key'        => $key,
            'type'       => $type,
            'token'      => $token ?: $this->generateUniqueToken($key),
            'expired_at' => $expiredAt ?: Carbon::now()->addSeconds(config('core::user.verify.expires', 600)),
        ]);

        $deleteOther = $options['delete_other'] ?? true;
        if ($deleteOther || ($options['expire_other'] ?? true)) {
            $verify->makeOtherExpired($deleteOther);
        }

        return $verify;
    }

    /**
     * @param User $user
     */
    protected function checkResetEmailAttempts(User $user)
    {
        $key = $user->id . '|reset_email';
        $maxAttempts = config('core::system.reset.email.maxAttempts', 3);
        $decaySeconds = config('core::system.reset.email.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'email' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetEmailAttempts($user);

        /** @var UserVerifyService $userVerifyService */
        $userVerifyService = resolve(UserVerifyService::class);

        /** @var UserVerify $verify */
        $verify = $userVerifyService->create($user, $email, 'reset_mobile', null, $options);
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
        $userVerify = $this->getUserVerify([
            'key' => $email,
            'token' => $token,
            'type' => 'reset_email'
        ], [
            'with' => ['user']
        ]);

        $userVerify->user->email = $email;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }

    /**
     * @param User $user
     */
    protected function checkResetMobileAttempts(User $user)
    {
        $key = $user->id . '|reset_mobile';
        $maxAttempts = config('core::system.reset.mobile.maxAttempts', 3);
        $decaySeconds = config('core::system.reset.mobile.decaySeconds', 600);
        if ($this->hasTooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'mobile' => [trans('请求次数太多')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }
        $this->incrementAttempts($key, $decaySeconds);
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

        $this->checkResetMobileAttempts($user);

        $token = $this->generateUniqueToken($mobile, function() {
            return random_int(100000, 999999);
        });
        $verify = $this->create($user, $mobile, 'reset_mobile', $token, $options);
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
        $userVerify = $this->getUserVerify([
            'key' => $mobile,
            'token' => $token,
            'type' => 'reset_mobile'
        ], [
            'with' => ['user']
        ]);

        $userVerify->user->mobile = $mobile;
        if (!$userVerify->user->save()) {
            throw ModelSaveException::withModel($userVerify->user);
        }

        $userVerify->setExpired()->save();

        return true;
    }
}
