<?php

namespace Modules\Core\Events\Frontend;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserEmailVerified.
 */
class UserEmailVerified
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
