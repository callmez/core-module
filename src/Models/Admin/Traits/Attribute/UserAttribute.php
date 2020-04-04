<?php

namespace Modules\Core\Models\Admin\Traits\Attribute;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttribute.
 */
trait UserAttribute
{
    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        // Note: Password Histories are logged from the \Modules\Core\Observer\User\UserObserver class
        $this->attributes['password'] = $this->generateHashByPassword($password);
    }

    /**
     * @param $password
     *
     * @return string
     */
    protected function generateHashByPassword($password)
    {
        // If password was accidentally passed in already hashed, try not to double hash it
        if (
            (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
        ) {
            $hash = $password;
        } else {
            $hash = Hash::make($password);
        }

        return $hash;
    }
}
