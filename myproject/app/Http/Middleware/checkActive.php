<?php
/**
 * Created by PhpStorm.
 * User: WalkeR
 * Date: 24/08/2017
 * Time: 14:07
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class checkActive
{
    public function handle($request, Closure $next)
    {
        if ($request->user()->active == 0) {
            Session::flush();
            return redirect('/auth/login');
        }
        return $next($request);
    }
}
