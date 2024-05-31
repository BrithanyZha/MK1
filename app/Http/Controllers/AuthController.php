<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if(Auth::user()->role === 'admin'){
                return redirect()->route('admin')->with('success', 'Halo Admin, Anda berhasil Login');
            } elseif(Auth::user()->role === 'user'){
                return redirect()->route('user')->with('success', 'Anda berhasil Login');
            }
            
        } else {
            return back()->withErrors('Email atau Password salah');
        }
    }

    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    
    return redirect('login')->with('success', 'Registration successful. Please login.');
}

public function logout(Request $request)
{
    Auth::logout();
    
    return view('auth.login');
}
}
