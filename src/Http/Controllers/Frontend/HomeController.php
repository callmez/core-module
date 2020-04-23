<?php

namespace Modules\Core\Http\Controllers\Frontend;

use Modules\Core\Http\Controllers\Controller;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{

    public function index()
    {
        $invitationService = resolve(\Modules\Core\Services\Frontend\UserInvitationService::class);
        return $invitationService->getInvitersByUser(2);

        return 'hello world!';
    }
}
