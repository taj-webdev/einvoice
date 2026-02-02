<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ================= LOGIN =================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('users')
            ->where('username', $request->username)
            ->where('is_active', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username atau Password salah');
        }

        // Simpan session
        Session::put('user_id', $user->id);
        Session::put('role_id', $user->role_id);
        Session::put('full_name', $user->full_name);

        return redirect('/dashboard')
            ->with('login_success', true)
            ->with('login_name', $user->full_name);
    }

    // ================= REGISTER =================
    public function showRegister()
    {
        $roles = DB::table('roles')->get();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'username'  => 'required|unique:users,username',
            'password'  => 'required|min:6',
            'role_id'   => 'required',
        ]);

        DB::table('users')->insert([
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'created_at' => now(),
        ]);

        return redirect('/login')
            ->with('register_success', true);
    }

    // ================= LOGOUT =================
    public function logout()
    {
        $name = Session::get('full_name');

        Session::flush();

        return redirect('/login')
            ->with('logout_success', true)
            ->with('logout_name', $name);
    }
}
