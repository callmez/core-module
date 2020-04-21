<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use App\Models\User;
use Modules\Core\Notifications\Frontend\UserNeedsConfirmation;
use Modules\Core\Repositories\Admin\Auth\UserRepository;

/**
 * Class UserConfirmationController.
 */
class UserConfirmationController extends Controller
{
    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function sendConfirmationEmail(ManageUserRequest $request, User $user)
    {
        // Shouldn't allow users to confirm their own accounts when the application is set to manual confirmation
        if (config('access.users.requires_approval')) {
            return redirect()->back()->withFlashDanger(__('alerts.admin.users.cant_resend_confirmation'));
        }

        if ($user->isConfirmed()) {
            return redirect()->back()->withFlashSuccess(__('exceptions.admin.access.users.already_confirmed'));
        }

        $user->notify(new UserNeedsConfirmation($user->confirmation_code));

        return redirect()->back()->withFlashSuccess(__('alerts.admin.users.confirmation_email'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function confirm(ManageUserRequest $request, User $user, UserRepository $userRepository)
    {
        $userRepository->confirm($user);

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.confirmed'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function unconfirm(ManageUserRequest $request, User $user, UserRepository $userRepository)
    {
        $userRepository->unconfirm($user);

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.admin.users.unconfirmed'));
    }
}
