<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // Import the User model

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect()->route('account.dashboard');
        } else {
            return redirect()->route('account.login')
                ->with('error', 'Either email or password is incorrect.')
                ->withInput();
        }
    }

    public function register()
    {
        return view('register');
    }

    public function processRegister(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required',
            'password'              => [
                'required',
                'string',
                'min:8',              // Minimum 8 characters
                'regex:/[a-z]/',      // At least one alphabet
                'regex:/[0-9]/',      // At least one number
                'confirmed'
            ],
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors($validator);
        }

        // Create new user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
            'role'     => 'customer',
        ]);

        // Redirect to login with success message
        return redirect()->route('account.login')->with('success', 'Registration successful. Please log in.');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login'); // Redirecting to login after logout
    }
}
