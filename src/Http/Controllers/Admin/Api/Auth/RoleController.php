<?php

namespace Modules\Core\Http\Controllers\Admin\Api\Auth;

use Modules\Core\Events\Admin\Auth\Role\RoleDeleted;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\Role\ManageRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use Modules\Core\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use Modules\Core\Models\Auth\Role;
use Modules\Core\Repositories\Admin\Auth\RoleRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoleController.
 */
class RoleController extends Controller
{
    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ManageRoleRequest $request, RoleRepository $roleRepository)
    {
        return $roleRepository
            ->where('guard_name', $request->get('guard', Auth::getDefaultDriver()))
            ->orderBy('sort')
            ->paginate();
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function withPermissions(ManageRoleRequest $request, Role $role)
    {
        $role->permissions;
        return $role;
    }

    /**
     * @param  StoreRoleRequest  $request
     *
     * @return mixed
     * @throws \Modules\Core\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request, RoleRepository $roleRepository)
    {
        return $roleRepository->create($request->only('name', 'title', 'permissions', 'sort'));
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
        return $roleRepository->update($role, $request->only('name', 'title', 'permissions', 'sort'));
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

        $roleRepository->deleteById($role->id);

        event(new RoleDeleted($role));

        return [];
    }
}
