<?php

namespace Modules\Core\Notifications\Middleware;

use Illuminate\Notifications\SendQueuedNotifications;

class BeforeSend
{
    public function handle($job, $next)
    {
        if ($job instanceof SendQueuedNotifications && method_exists($job->notification, 'beforeSend') ) {
            $job->notification->beforeSend($job);
        }

        $next($job);
    }
}
