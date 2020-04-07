<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\Auth\UserRegistered;
use Modules\Core\Http\Requests\Frontend\Auth\RegisterRequest;
use Modules\Core\Services\Frontend\UserService;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param UserService $userService
     *
     * @return User
     */
    public function register(RegisterRequest $request, UserService $userService)
    {
        return $userService->register($request->validationData());
    }

}
