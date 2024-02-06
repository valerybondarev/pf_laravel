<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserIndexRequest;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Arr;
use Auth;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:user-index', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(UserIndexRequest $request): View
    {
        $query = User::query()
            ->when(!empty($request->filter['query']), function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->filter['query'] . '%')
                    ->orWhere('email', 'like', '%' . $request->filter['query'] . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        $users = $query;

        return view('admin.users.index', [
            'users' => $users,
            'filter' => $request->filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::query()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        /** @var User $user */
        $user = User::query()->create($input);
        $user->assignRole($request->input('roles'));

        event(new Registered($user));

        return redirect()->route('admin.users.index')
            ->with('success', __('User created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::query()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        if (empty($input['email_verified_at'])) {
            $input['email_verified_at'] = null;
        }

        $user->update($input);

        $user->syncRoles($request->input('roles'));

        return redirect()->route('admin.users.index')
            ->with('success', __('User updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', __('User deleted successfully'));
    }

    public function impersonate(User $user)
    {
        Auth::user()->impersonate($user);
        return redirect()->route('home');
    }
}
