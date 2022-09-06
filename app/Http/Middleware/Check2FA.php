<?php

namespace App\Http\Middleware;

use App\Http\Controllers\api\BaseController;
use Closure;
use Illuminate\Http\Request;
use Session;

class Check2FA extends BaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_2fa')) {
            return $this->sendError('2fa not authenticate',
            [
                'success' => false,
                'message' => 'authenticate your self first'
            ],401 );
        }
        return $next($request);
    }
}
