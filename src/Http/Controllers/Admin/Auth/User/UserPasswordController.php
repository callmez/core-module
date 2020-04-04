<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use Modules\Core\Http\Requests\Admin\Auth\User\UpdateUserPasswordRequest;
use Modules\Core\Models\Auth\BaseUser;
use Modules\Core\Repositories\Admin\Auth\UserRepository;

/**
 * Class UserPasswordController.
 */
class UserPasswordController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     * @param BaseUser              $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, BaseUser $user)
    {
        return view('core::admin.auth.user.change-password')
            ->withUser($user);
    }

    /**
     * @param UpdateUserPasswordRequest $request
     * @param BaseUser                      $user
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateUserPasswordRequest $request, BaseUser $user)
    {
        $this->userRepository->updatePassword($user, $request->only('password'));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.updated_password'));
    }
}
