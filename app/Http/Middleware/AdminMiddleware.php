<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        if (Session::get('role_id') != 1) {
            abort(403, 'Akses khusus Admin');
        }

        return $next($request);
    }
}
