<?php

namespace Modules\Core\Http\Controllers\Admin\Auth\User;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Auth\User\ManageUserRequest;
use Modules\Core\Models\Auth\SocialAccount;
use Modules\Core\Models\Auth\User;
use Modules\Core\Repositories\Admin\Access\User\SocialRepository;

/**
 * Class UserSocialController.
 */
class UserSocialController extends Controller
{
    /**
     * @param ManageUserRequest $request
     * @param SocialRepository  $socialRepository
     * @param User              $user
     * @param SocialAccount     $social
     *
     * @throws \Modules\Core\Exceptions\GeneralException
     * @return mixed
     */
    public function unlink(ManageUserRequest $request, SocialRepository $socialRepository, User $user, SocialAccount $social)
    {
        $socialRepository->delete($user, $social);

        return redirect()->back()->withFlashSuccess(__('alerts.admin.users.social_deleted'));
    }
}
