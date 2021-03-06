<?php

namespace Modules\Core\Listeners\Frontend;

use Modules\Core\Events\Frontend\UserConfirmed;
use Modules\Core\Events\Frontend\UserLoggedIn;
use Modules\Core\Events\Frontend\UserLoggedOut;
use Modules\Core\Events\Frontend\UserProviderRegistered;
use Modules\Core\Events\Frontend\UserRegistered;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param $event
     */
    public function onLoggedIn($event)
    {
        $ip_address = request()->getClientIp();

        // Update the logging in users time & IP
        $event->user->fill([
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $ip_address,
        ]);

        // Update the timezone via IP address
        $geoip = geoip($ip_address);

        if ($event->user->timezone !== $geoip['timezone']) {
            // Update the users timezone
            $event->user->fill([
                'timezone' => $geoip['timezone'],
            ]);
        }

        $event->user->save();

        logger('User Logged In: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onLoggedOut($event)
    {
        logger('User Logged Out: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onRegistered($event)
    {
        logger('User Registered: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onProviderRegistered($event)
    {
        logger('User Provider Registered: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onConfirmed($event)
    {
        logger('User Confirmed: '.$event->user->full_name);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserLoggedIn::class,
            'Modules\Core\Listeners\Frontend\UserEventListener@onLoggedIn'
        );

//        $events->listen(
//            UserLoggedOut::class,
//            'Modules\Core\Listeners\Frontend\UserEventListener@onLoggedOut'
//        );
//
//        $events->listen(
//            UserRegistered::class,
//            'Modules\Core\Listeners\Frontend\UserEventListener@onRegistered'
//        );
//
//        $events->listen(
//            UserProviderRegistered::class,
//            'Modules\Core\Listeners\Frontend\UserEventListener@onProviderRegistered'
//        );
//
//        $events->listen(
//            UserConfirmed::class,
//            'Modules\Core\Listeners\Frontend\UserEventListener@onConfirmed'
//        );
    }
}
