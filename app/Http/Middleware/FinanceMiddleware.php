<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FinanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        if (!in_array(Session::get('role_id'), [1, 2])) {
            abort(403, 'Akses khusus Finance & Admin');
        }

        return $next($request);
    }
}
