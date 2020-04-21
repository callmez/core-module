<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use App\Models\User;
use Modules\Core\Repositories\Admin\Auth\UserRepository;

/**
 * Class UserStatusController.
 */
class UserStatusController extends Controller
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
     *
     * @return mixed
     */
    public function getDeactivated(ManageUserRequest $request)
    {
        return view('core::admin.auth.user.deactivated')
            ->withUsers($this->userRepository->getInactivePaginated(25, 'id', 'asc'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function getDeleted(ManageUserRequest $request)
    {
        return view('core::admin.auth.user.deleted')
            ->withUsers($this->userRepository->getDeletedPaginated(25, 'id', 'asc'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     * @param                   $status
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function mark(ManageUserRequest $request, User $user, $status)
    {
        $this->userRepository->mark($user, (int) $status);

        return redirect()->route(
            (int) $status === 1 ?
            'admin.auth.user.index' :
            'admin.auth.user.deactivated'
        )->withFlashSuccess(__('alerts.admin.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $deletedUser
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function delete(ManageUserRequest $request, User $deletedUser)
    {
        $this->userRepository->forceDelete($deletedUser);

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.admin.users.deleted_permanently'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $deletedUser
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function restore(ManageUserRequest $request, User $deletedUser)
    {
        $this->userRepository->restore($deletedUser);

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.restored'));
    }
}
