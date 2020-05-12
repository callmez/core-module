<?php

namespace Modules\Core\Http\Controllers\Frontend\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Services\Frontend\UserInvitationService;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, UserInvitationService $userInvitationService)
    {
        $user = $request->user();
        return $userInvitationService->getUserInvitationList($user);
    }


    /**
     * @param Request $request
     * @param UserInvitationService $userInvitationService
     * @return bool|\Modules\Core\Models\Frontend\UserInvitation
     */
    public function store(Request $request, UserInvitationService $userInvitationService)
    {
        $user = $request->user();
        return $userInvitationService->createWithUser($user);
    }

}
