<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\Role;

use Modules\Core\Events\Admin\Auth\Role\RoleDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\Role\ManageRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use Modules\Core\Models\Auth\Role;
use Modules\Core\Repositories\Admin\Auth\PermissionRepository;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;

/**
 * Class RoleController.
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ManageRoleRequest $request)
    {
        return view('admin.auth.role.index');
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ManageRoleRequest $request)
    {
        return view('admin.auth.role.edit');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @return mixed
     */
    public function edit(ManageRoleRequest $request, Role $role)
    {

        return view('admin.auth.role.edit')
            ->withRole($role);
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageRoleRequest $request, Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.roles')->withFlashDanger(__('exceptions.admin.access.roles.cant_delete_admin'));
        }

        $this->roleRepository->deleteById($role->id);

        event(new RoleDeleted($role));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.deleted'));
    }
}
