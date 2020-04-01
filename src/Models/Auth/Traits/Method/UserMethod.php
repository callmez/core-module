<?php

namespace Modules\Core\Models\Auth\Traits\Method;

use Hash;
use Carbon\Carbon;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @param bool $size
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @return bool|\Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getPicture($size = false)
    {
        return false;
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
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
    public function isAuth()
    {
        return $this->auth;
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
     * @param $email
     *
     * @return $this
     */
    public function setMobileVerified($email)
    {
        $this->mobile = $email;
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
