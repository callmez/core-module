<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use Modules\Core\Events\Admin\Auth\Role\RoleDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\Permission\ManagePermissionRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use Modules\Core\Models\Admin\AdminPermission;
use Modules\Core\Repositories\Admin\Auth\PermissionRepository;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;
use function GuzzleHttp\Promise\all;

/**
 * Class PermissionController.
 */
class PermissionController extends Controller
{
    /**
     * @param ManagePermissionRequest $request
     *
     * @return mixed
     */
    public function index(ManagePermissionRequest $request, PermissionRepository $permissionRepository)
    {
        return $permissionRepository
            ->where('guard_name', $request->get('guard', 'admin'))
            ->orderBy('sort')
            ->get();
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ManageRoleRequest $request, PermissionRepository $permissionRepository)
    {
        return view('core::admin.auth.role.create')
            ->withPermissions($permissionRepository->get());
    }

    /**
     * @param  StoreRoleRequest  $request
     *
     * @return mixed
     * @throws \Modules\Core\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleRepository->create($request->only('name', 'associated-permissions', 'permissions', 'sort'));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.created'));
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @return mixed
     */
    public function edit(ManageRoleRequest $request, Role $role, PermissionRepository $permissionRepository)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.roles')->withFlashDanger('You can not edit the administrator role.');
        }

        return view('core::admin.auth.role.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->pluck('name')->all())
            ->withPermissions($permissionRepository->get());
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     *
     * @return mixed
     * @throws \Modules\Core\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role, RoleRepository $roleRepository)
    {
        $roleRepository->update($role, $request->only('name', 'permissions'));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.updated'));
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageRoleRequest $request, Role $role, RoleRepository $roleRepository)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.roles')->withFlashDanger(__('exceptions.admin.access.roles.cant_delete_admin'));
        }

        $roleRepository->deleteById($role->id);

        event(new RoleDeleted($role));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.deleted'));
    }
}
