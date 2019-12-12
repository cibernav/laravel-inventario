<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
		//se ejecuta antes de validar
		
		//valida si username es user o email
		//https://codebriefly.com/laravel-authentication-with-username-or-email/
		/* $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';
			
		return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ]; */
		
        $request['estado'] = 1;
        return $request->only($this->username(), 'password', 'estado');
    }

    protected function authenticated(Request $request, $user)
    {
        //
		// se ejecuta despues de validar
		
        //$id = Auth::user()->id;
            //$user = User::find(2);
        $user->fechalogin = Carbon::now();
        $user->save();

        //dd($user->id);

        return redirect()->intended($this->redirectTo);
    }

}
