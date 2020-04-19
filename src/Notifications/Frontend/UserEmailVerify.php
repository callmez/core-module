<?php

namespace Modules\Core\Notifications\Frontend;

use Modules\Core\Models\Frontend\UserVerify;
use Modules\Core\Messages\Frontend\UserVerifyEmailMessage;
use Modules\Core\Notifications\Middleware\BeforeSend;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEmailVerify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var UserVerify
     */
    protected $userVerify;

    /**
     * UserEmailVerify constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $notifiable->withNotificationEmail($this->userVerify->key);
        return new UserVerifyEmailMessage($this->userVerify);
    }
}
