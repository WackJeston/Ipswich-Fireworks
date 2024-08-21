<?php

namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\Auth\ResetPassword;
use App\Models\User;
use App\Models\PasswordReset;

class AuthController extends AdminController
{
	public function show()
  {
    return view('admin/auth/login');
  }

  public function authenticate(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
    ]);

    $admin = DB::select(sprintf('SELECT
      u.admin
      FROM users AS u
      WHERE u.email = "%s"
      LIMIT 1
    ', $request->email));

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, !is_null($request->remember)) && $admin[0]->admin == 1) {
      $request->session()->regenerate();

      return redirect()->intended('/admin/dashboard');
    }

    return redirect("/admin")->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }

  public function logout() {
    Session::flush();
    Auth::logout();

    return Redirect('/admin');
  }

	public function forgotPassword()
	{
		return view('admin/auth/forgot-password');
	}

	public function forgotPasswordEmail(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
		]);

		$user = User::where('email', $request->email)->where('admin', 1)->first();

		if ($user) {
			PasswordReset::create(['email' => $user->email, 'token' => md5($user->email), 'created_at' => date('Y-m-d H:i:s')]);

			Mail::to($user->email)->send(new ResetPassword($user));
		}

		return redirect('/admin')->with('message', 'Password reset link has been sent to your email.');
	}

	public function resetPassword(string $email, string $token)
	{
		$this->checkToken($email, $token);

		return view('admin/auth/reset-password', compact(
			'email',
			'token',
    ));
	}

	public function resetPassword2(Request $request, string $email, string $token)
	{
		$this->checkToken($email, $token);

		$request->validate([
			'password' => 'required|min:8',
			'confirm-password' => 'required|same:password',
		]);

		$user = User::where('email', $email)->where('admin', 1)->first();

		if ($user) {
			$user->password = Hash::make($request->password);
			$user->save();

			PasswordReset::where('email', $email)->delete();
		}

		return redirect('/admin')->with('message', 'Password has been reset.');
	}

	private function checkToken(string $email, string $token) {
		$tokenRef = PasswordReset::select('token')->where('email', $email)->orderBy('created_at', 'DESC')->first()['token'];

		if (empty($tokenRef) || empty($token) || $token != $tokenRef) {
			return redirect('/admin')->withErrors(['token' => 'Invalid token.']);
		}
	}
}
