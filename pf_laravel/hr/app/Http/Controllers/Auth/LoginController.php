<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

    public function __construct() {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Форма входа в личный кабинет
     */
    public function login() {
        if (Auth::check()){
            return redirect()->route('user.index');
        }
        return view('auth.login');
    }

    /**
     * Аутентификация пользователя
     */
    public function authenticate(Request $request) {
        $this->middleware('guest', ['except' => 'logout']);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user->role_id === 2) {
                return redirect()->route('user.edit', $user->id);
            }
            return redirect()
                ->route('user.index')
                ->with('success', 'Вы вошли в личный кабинет');
        }

        return redirect()
            ->route('login')
            ->withErrors('Неверный логин или пароль');
    }

    /**
     * Выход из личного кабинета
     */
    public function logout() {
        Auth::logout();
        return redirect()
            ->route('login')
            ->with('success', 'Вы вышли из личного кабинета');
    }
}
