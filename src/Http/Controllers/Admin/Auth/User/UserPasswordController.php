<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use Modules\Core\Http\Requests\Admin\Auth\User\UpdateUserPasswordRequest;
use Modules\Core\Models\Auth\User;
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
     * @param User              $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, User $user)
    {
        return view('admin.auth.user.change-password')
            ->withUser($user);
    }

    /**
     * @param UpdateUserPasswordRequest $request
     * @param User                      $user
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateUserPasswordRequest $request, User $user)
    {
        $this->userRepository->updatePassword($user, $request->only('password'));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.updated_password'));
    }
}
