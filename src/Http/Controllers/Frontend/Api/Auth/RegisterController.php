<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use App\Models\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Services\Frontend\UserRegisterService;
use Modules\Core\Http\Requests\Frontend\Auth\RegisterRequest;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param UserRegisterService $userRegiseterService
     *
     * @return User
     */
    public function register(RegisterRequest $request, UserRegisterService $userRegiseterService)
    {
        return $userRegiseterService->register($request->validationData());
    }
}
