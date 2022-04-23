<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Route;

class EnsureUserRoleIsAllowedToAccess
{
    // Dashboard,dll

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try
        {
            $userRole=auth()->user()->role;
            $currentRouteName=Route::currentRouteName();
    
            if(UserPermission::isRoleHasRightToAccess($userRole,$currentRouteName)
                ||in_array($currentRouteName,$this->defaultUserAccessRole()[$userRole])){
                return $next($request);
            }else{
                abort(403,'Unauthorize Action');
            }
        }
        catch(\throwable $th)
        {
            abort(403,'YOU ARE NOT ALLOWED HERE');
        }
    }
    
    /**
     * List of accessible resource
     *
     * @return void
     */
    private function defaultUserAccessRole()
    {
        return [
            'admin'=>[
                'user-permissions',
            ],
        ];
    }
}
