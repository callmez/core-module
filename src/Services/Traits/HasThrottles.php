<?php

namespace Modules\Core\src\Services\Traits;

use Illuminate\Cache\RateLimiter;

trait HasThrottles
{
    /**
     * @return RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * @param $key
     * @param int $maxAttempts
     *
     * @return bool
     */
    protected function hasTooManyAttempts($key, $maxAttempts = 3)
    {
        return $this->limiter()->tooManyAttempts($key, $maxAttempts);
    }


    /**
     * @param $key
     * @param int $decaySeconds
     */
    protected function incrementAttempts($key, $decaySeconds = 600)
    {
        $this->limiter()->hit($key, $decaySeconds);
    }

    /**
     * @param $key
     */
    protected function clearLoginAttempts($key)
    {
        $this->limiter()->clear($key);
    }
}
