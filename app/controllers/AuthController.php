<?php
// Laradev - a Laravel web app boilerplate 
// Copyright (c) 2014 Deved S.a.s. di Salvatore Guarino & C - sg@deved.it

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

class AuthController extends BaseController {

	public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

	public function login()
	{
		return View::make('login')->with('bodyClass', 'bg-black');
	}

	public function authenticate()
	{
		$email=Input::get('email');
		$password=Input::get('password');
		$remember = Input::get('remember_me') ? true : false;
		if (Auth::attempt(array('email' => $email, 'password' => $password, 'active' => 1), $remember))
			{
			    return Redirect::intended('admin');
			}
		else {
				return Redirect::to('login')->with('error', 'Login Failed');
			}
	}

	public function signup()
	{
		return View::make('signup')->with('bodyClass', 'bg-black');
	}

	public function registerUser()
	{
		$input = Input::all();
		var_dump($input);
		if ($input['password'] === $input['password_confirmation'])
		{
			$user = new User;
			$user->email = $input['email'];
			$user->password = Hash::make($input['password']);
			$user->active = false;
			if ($user->save()) {
				$confirmation_code = Crypt::encrypt($user->email);
				Mail::send('emails.auth.confirm', array('confirmation_code' => $confirmation_code), function($message) use ($user){
    						$message->to($user->email, $user->email)->subject('Welcome! Please confirm your account!');
    					});
				return Redirect::to('login')->with('status', trans('You are registered. Please check your email to confirm the registration.'));
			} else {
				return Redirect::to('login')->with('error', trans('Registriation failed.'));
			}
		}

	}

	public function confirmUser($code)
	{
		$email = Crypt::decrypt($code);
		$user = User::where('email', '=', $email)->first();
		if ($user->active)
		{
			return Redirect::to('login')->with('error', trans('User already active!'));
		}
		$user->active = true;
		$user->save();
		return Redirect::to('login')->with('status', trans('Congratulation, your account is actived. Please login to proceed'));
	}

	public function logout()
	{
		Auth::logout();
		return Redirect::intended('login');
	}

}