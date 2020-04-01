<?php

namespace Modules\Core\Listeners\Admin\Auth\User;

use Modules\Core\Events\Admin\Auth\User\UserConfirmed;
use Modules\Core\Events\Admin\Auth\User\UserCreated;
use Modules\Core\Events\Admin\Auth\User\UserDeactivated;
use Modules\Core\Events\Admin\Auth\User\UserDeleted;
use Modules\Core\Events\Admin\Auth\User\UserPasswordChanged;
use Modules\Core\Events\Admin\Auth\User\UserPermanentlyDeleted;
use Modules\Core\Events\Admin\Auth\User\UserReactivated;
use Modules\Core\Events\Admin\Auth\User\UserRestored;
use Modules\Core\Events\Admin\Auth\User\UserSocialDeleted;
use Modules\Core\Events\Admin\Auth\User\UserUnconfirmed;
use Modules\Core\Events\Admin\Auth\User\UserUpdated;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        logger('User Created');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        logger('User Updated');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        logger('User Deleted');
    }

    /**
     * @param $event
     */
    public function onConfirmed($event)
    {
        logger('User Confirmed');
    }

    /**
     * @param $event
     */
    public function onUnconfirmed($event)
    {
        logger('User Unconfirmed');
    }

    /**
     * @param $event
     */
    public function onPasswordChanged($event)
    {
        logger('User Password Changed');
    }

    /**
     * @param $event
     */
    public function onDeactivated($event)
    {
        logger('User Deactivated');
    }

    /**
     * @param $event
     */
    public function onReactivated($event)
    {
        logger('User Reactivated');
    }

    /**
     * @param $event
     */
    public function onSocialDeleted($event)
    {
        logger('User Social Deleted');
    }

    /**
     * @param $event
     */
    public function onPermanentlyDeleted($event)
    {
        logger('User Permanently Deleted');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        logger('User Restored');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserCreated::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onCreated'
        );

        $events->listen(
            UserUpdated::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onUpdated'
        );

        $events->listen(
            UserDeleted::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onDeleted'
        );

        $events->listen(
            UserConfirmed::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onConfirmed'
        );

        $events->listen(
            UserUnconfirmed::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onUnconfirmed'
        );

        $events->listen(
            UserPasswordChanged::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onPasswordChanged'
        );

        $events->listen(
            UserDeactivated::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onDeactivated'
        );

        $events->listen(
            UserReactivated::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onReactivated'
        );

        $events->listen(
            UserSocialDeleted::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onSocialDeleted'
        );

        $events->listen(
            UserPermanentlyDeleted::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onPermanentlyDeleted'
        );

        $events->listen(
            UserRestored::class,
            'Modules\Core\Listeners\Admin\Auth\User\UserEventListener@onRestored'
        );
    }
}
