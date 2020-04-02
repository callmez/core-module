<?php

namespace Modules\Core\Auth\Guards;

use Laravel\Sanctum\Guard;
use Illuminate\Http\Request;

class AdminGuard extends Guard
{
    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $defaultGuard = config('sanctum.guard', 'web'); // 记录默认guard值
        config(['sanctum.guard' => 'admin_web']); // 替换为后台值

        $result = parent::__invoke($request);

        config(['sanctum.guard' => $defaultGuard]); // 替换默认值
        return $result;
    }
}
