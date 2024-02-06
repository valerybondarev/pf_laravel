<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Levels;
use App\Models\Specialization;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Source;
use App\Models\Status;
use App\Models\Skill;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {

    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Форма регистрации
     */
    public function register(Request $request) {
        $data = $request->all();
        if (!isset($data['token']) || !$data['token'])
            return redirect()
                ->route('login')
                ->withErrors('Не передан токен');

        //сделать проверку токена


        if (isset($data['email']) && $data['email']) {
            $user = User::where('email', trim(strip_tags($data['email'])))->first();
            $worker = User::find($user->id);
            $worker->worker = new Worker();
        } else
            $worker = new Worker();



        $roles = Role::all();
        $statuses = Status::all();
        $sources = Source::all();
        $skills = Skill::all();
        $specializations = Specialization::all();
        $levels = Levels::all();
        $languages = Language::all();

        //dd($worker);

        return view('user.create', compact('worker', 'roles', 'statuses', 'sources', 'skills', 'specializations', 'levels', 'languages'));
    }

    /**
     * Добавление пользователя
     */
    public function create(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $worker = User::where('email', $request->email)->first();
        //dd($worker);
        if ($worker->password)
            return redirect()->route('login')->withErrors('Пользователь с таким E-mail уже зарегистрирован');

        $user = User::find($worker->id);
        $data = $request->all();
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = $user->password;
        }
        $user->role_id = 2;
        $user->update($data);

        $data['user_id'] = $user->id;
        $data['status_id'] = Status::getDefaultStatus();

        $worker = new Worker();
        if ($worker->saveWorker($data)) {
            return redirect()
                ->route('login')
                ->with('success', 'Вы успешно зарегистрировались');
        }

        return redirect()
            ->route('login')
            ->withErrors('Ошибка регистрации');

    }
}
