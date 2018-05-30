<?php

namespace App\Http\Controllers\Merchants\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/merchant';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:merchant', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('merchants.pages.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('merchant')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            //If successfull then redirect to their intended location
            return redirect()->intended(route('merchant.home'));
        }
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('merchant')->logout();
        return redirect('/');
    }
}
