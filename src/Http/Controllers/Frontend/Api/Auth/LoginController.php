<?php

namespace Modules\Core\Http\Controllers\Frontend\Api\Auth;

use Hash;
use Modules\Core\Models\Auth\User;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Events\Frontend\Auth\UserLoggedIn;
use Modules\Core\Exceptions\Frontend\Auth\UserEmailVerifyException;
use Modules\Core\Exceptions\Frontend\Auth\UserMobileVerifyException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;


/**
 * @OA\Tag(
 *     name="Auth",
 *     description="登录",
 * )
 */
class LoginController extends Controller
{
    use ThrottlesLogins;

    public function username()
    {
        return 'username';
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="用户密码登录",
     *     description="提交账号密码登录",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     description="用户名",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="密码",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="captcha",
     *                     description="图片验证码",
     *                     type="string",
     *                 ),
     *                  @OA\Property(
     *                     property="device_name",
     *                     description="设备类型",
     *                     type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功登录"
     *     ),
     * )
     */

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $key = $this->username();

        $request->validate([
            $key => 'required|string',
            'password' => 'required',
            'device' => 'string'
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse();
        }

        $username = $request->$key;

        $isEmail = $isMobile = false;

        if (filter_var($username, FILTER_VALIDATE_EMAIL )) {
            $isEmail = true;

            $query = User::where('email', $username);
        } elseif (is_numeric($username) && mb_strlen($username) == 11) {
            $isMobile = true;

            $query = User::where('mobile', $username);
        } else {
            $query = User::where('username', $username);
        }

        /** @var User $user */
        $user = $query->first();

        if (! $user || ! $user->checkPassword($request->password)) {

            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                'username' => [trans('auth.failed')],
            ]);
        }

        if ($isEmail && !$user->isEmailVerified()) {

            throw new UserEmailVerifyException();

        } elseif ($isMobile && !$user->isMobileverified()) {

            throw new UserMobileVerifyException();

        }

        $this->clearLoginAttempts($request);

        event(new UserLoggedIn($user));

        return [
            'access_token' => $user->createToken($request->device ?: 'frontend')->plainTextToken
        ];
    }
}
