<?php
/**
 * Created date 31.03.2021
 *
 * @author Sergey Tyrgola <ts@goldcarrot.ru>
 */

namespace App\Http\Admin\Controllers\Auth;


use App\Domain\Authentication\Services\WebAuthenticationService;
use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private WebAuthenticationService $authenticationService
    )
    {
    }

    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if ($this->authenticationService->login($request->credentials())) {
            return redirect()->intended(route('admin.dashboard'));
        }
        throw ValidationException::withMessages(['username' => __('auth.failed')]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authenticationService->logout($request->user());
        return redirect(route('admin.auth.sign-in-form'));
    }
}
