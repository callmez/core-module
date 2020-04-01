<?php

namespace Modules\Core\Repositories\Admin\Auth;

use Modules\Core\Events\Admin\Auth\Role\RoleCreated;
use Modules\Core\Events\Admin\Auth\Role\RoleUpdated;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Models\Auth\Role;
use Modules\Core\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleRepository.
 */
class RoleRepository extends BaseRepository
{
    /**
     * RoleRepository constructor.
     *
     * @param  Role  $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Throwable
     * @return Role
     */
    public function create(array $data): Role
    {
        // Make sure it doesn't already exist
        if ($this->roleExists($data['name'])) {
            throw new GeneralException('A role already exists with the name '.e($data['name']));
        }

        if (! isset($data['permissions']) || ! \count($data['permissions'])) {
            $data['permissions'] = [];
        }

        //See if the role must contain a permission as per config
        if (config('access.roles.role_must_contain_permission') && \count($data['permissions']) === 0) {
            throw new GeneralException(__('exceptions.admin.access.roles.needs_permission'));
        }

        return DB::transaction(function () use ($data) {
            $role = $this->model::create([
                'name' => strtolower($data['name']),
                'title' => trim($data['title']),
                'sort' => intval($data['sort'])
            ]);

            if ($role) {
                $role->givePermissionTo($data['permissions']);

                event(new RoleCreated($role));

                return $role;
            }

            throw new GeneralException(trans('exceptions.admin.access.roles.create_error'));
        });
    }

    /**
     * @param Role  $role
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(Role $role, array $data): Role
    {
        // If the name is changing make sure it doesn't already exist
        if ($role->name !== strtolower($data['name'])) {
            if ($role->name == 'admin') {
                throw new GeneralException('Can not change name of role admin');
            }
            if ($this->roleExists($data['name'])) {
                throw new GeneralException('A role already exists with the name '.$data['name']);
            }
        }

        if (! isset($data['permissions']) || ! \count($data['permissions'])) {
            $data['permissions'] = [];
        }

        //See if the role must contain a permission as per config
        if (config('access.roles.role_must_contain_permission') && \count($data['permissions']) === 0) {
            throw new GeneralException(__('exceptions.admin.access.roles.needs_permission'));
        }

        return DB::transaction(function () use ($role, $data) {
            if ($role->update([
                'name' => strtolower($data['name']),
                'title' => trim($data['title']),
                'sort' => intval($data['sort'])
            ])) {
                $role->syncPermissions($data['permissions']);

                event(new RoleUpdated($role));

                return $role;
            }

            throw new GeneralException(trans('exceptions.admin.access.roles.update_error'));
        });
    }

    public function deleteById($id)
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        if ($model->name == 'admin') {
            throw new GeneralException('Can not delete role admin');
        }

        return $model->delete();
    }

    /**
     * @param $name
     *
     * @return bool
     */
    protected function roleExists($name): bool
    {
        return $this->model
            ->where('name', strtolower($name))
            ->count() > 0;
    }
}
