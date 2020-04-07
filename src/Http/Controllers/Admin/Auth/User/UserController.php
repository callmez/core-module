<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use App\Models\User;
use Modules\Core\Events\Admin\Auth\User\UserDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use Modules\Core\Http\Requests\Admin\Auth\User\StoreUserRequest;
use Modules\Core\Http\Requests\Admin\Auth\User\UpdateUserRequest;
use Modules\Core\Repositories\Admin\Auth\PermissionRepository;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;
use Modules\Core\Repositories\Admin\Auth\UserRepository;

/**
 * Class UserController.
 */
class UserController extends Controller
{

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request, UserRepository $userRepository)
    {
        return view('core::admin.auth.user.index')
            ->withUsers($userRepository->getActivePaginated(25, 'id', 'asc'));
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        return view('core::admin.auth.user.create')
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $request, UserRepository $userRepository)
    {
        $userRepository->create($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param BaseUser              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, BaseUser $user)
    {
        return view('core::admin.auth.user.show')
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param BaseUser                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        return view('core::admin.auth.user.edit')
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param BaseUser              $user
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, BaseUser $user)
    {
        $this->userRepository->update($user, $request->only(
            'first_name',
            'last_name',
            'email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param BaseUser              $user
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageUserRequest $request, BaseUser $user, UserRepository $userRepository)
    {
        $userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.admin.users.deleted'));
    }
}
