<?php

namespace Modules\Core\Models\Frontend\Traits\Method;

use Hash;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
//use Modules\Core\Models\Frontend\UserVerify;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function canResetEmail()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canResetPassword()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canResetPayPassword()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function isAuthVerified()
    {
        return $this->auth_verified_at != null;
    }

    /**
     * @return bool 是否验证邮箱
     */
    public function isEmailVerified()
    {
        return $this->email_verified_at != null;
    }

    /**
     * @return bool 是否验证手机号
     */
    public function isMobileVerified()
    {
        return $this->mobile_verified_at != null;
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function setEmailVerified($email)
    {
        $this->email = $email;
        $this->email_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @param $mobile
     *
     * @return $this
     */
    public function setMobileVerified($mobile)
    {
        $this->mobile = $mobile;
        $this->mobile_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @return $this;
     */
    public function setAuthVerified()
    {
        $this->auth = 1;
        $this->auth_verified_at = Carbon::now();

        return $this;
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->getAuthPassword());
    }

    /**
     * @param $payPassword
     *
     * @return bool
     */
    public function checkPayPassword($payPassword)
    {
        return Hash::check($payPassword, $this->pay_password);
    }
}
