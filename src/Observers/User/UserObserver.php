<?php

namespace Modules\Core\Observers\User;

use Modules\Core\Models\Frontend\BaseUser;

/**
 * Class UserObserver.
 */
class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  \Modules\Core\Models\Frontend\BaseUser  $user
     */
    public function created(BaseUser $user): void
    {
        $this->logPasswordHistory($user);
    }

    /**
     * Listen to the User updated event.
     *
     * @param  \Modules\Core\Models\Frontend\BaseUser  $user
     */
    public function updated(BaseUser $user): void
    {
        // Only log password history on update if the password actually changed
        if ($user->isDirty('password')) {
            $this->logPasswordHistory($user);
        }
    }

    /**
     * @param BaseUser $user
     */
    private function logPasswordHistory(BaseUser $user): void
    {
        if (config('access.users.password_history')) {
            $user->passwordHistories()->create([
                'password' => $user->password, // Password already hashed & saved so just take from model
            ]);
        }
    }
}
