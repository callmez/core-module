<?php

namespace Modules\Core\Models\Frontend\Traits\Notification;

use Modules\Core\Models\Frontend\UserVerify;
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

    /**
     * @param UserVerify $verify
     */
    public function sendEmailVerifyNotification(UserVerify $verify)
    {
        $this->notify(new UserEmailVerify($verify));
    }

    /**
     * @param UserVerify $verify
     */
    public function sendMobileVerifyNotification(UserVerify $verify)
    {
        $this->notify(new UserMobileVerify($verify));
    }

    /**
     * @param $mobile
     *
     * @return $this
     */
    public function withNotificationMobile($mobile)
    {
        $this->notificationMobile = $mobile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForEasySms()
    {
        return $this->notificationMobile ?: $this->mobile;
    }

    /**
     * @param $mobile
     *
     * @return $this
     */
    public function withNotificationEmail($mobile)
    {
        $this->notificationMobile = $mobile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->notificationMail ?: $this->email;
    }
}
