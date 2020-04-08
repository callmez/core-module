<?php

namespace Modules\Core\Models\Auth\Traits\Method;

use Modules\Core\Models\Auth\UserVerify;
use Modules\Core\Notifications\Frontend\Auth\UserEmailVerify;
use Modules\Core\Notifications\Frontend\Auth\UserMobileVerify;

trait UserNotification
{
    /**
     * @var string|int
     */
    public $notificationMobile;
    /**
     * @var string|int
     */
    public $notificationMail;

    public function sendEmailVerifyNotification(UserVerify $verify)
    {
        $this->notify(new UserEmailVerify($verify));
    }

    public function sendMobileVerifyNotification(UserVerify $verify)
    {
        $this->notify(new UserMobileVerify($verify));
    }

    public function withNotificationMobile($mobile)
    {
        $this->notificationMobile = $mobile;

        return $this;
    }

    public function routeNotificationForEasySms()
    {
        return $this->notificationMobile ?: $this->mobile;
    }

    public function withNotificationEmail($mobile)
    {
        $this->notificationMobile = $mobile;

        return $this;
    }

    public function routeNotificationForMail()
    {
        return $this->notificationMail ?: $this->email;
    }
}
