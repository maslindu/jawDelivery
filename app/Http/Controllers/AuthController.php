<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Enums\RoleEnum;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|unique:users,username|max:255|regex:/^\S*$/',
            'email'     => 'required|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/|unique:users,email',
            'phone'     => [
                'required',
                'unique:users,phone',
                'regex:/^\+?[0-9]{10,15}$/'
            ],
            'fullName'  => 'required|max:255',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors([
                'login_error' => $validator->errors()->first()
            ])->withInput($request->except('password', 'password_confirmation'));
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
            $user = User::create([
                'username' => $validatedData['username'],
                'email'    => $validatedData['email'],
                'phone'    => $validatedData['phone'],
                'fullName' => $validatedData['fullName'],
                'password' => $validatedData['password'],
            ]);

            // Attach role pelanggan
            $role = Role::where('name', RoleEnum::PELANGGAN->value)->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }
            
            Auth::login($user);
            return redirect("/dashboard");

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'login_error' => 'An unexpected error occurred during registration. Please try again.'
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors([
                'login_error' => 'Invalid username or password.'
            ])->withInput($request->only('username'));
        }

        Auth::login($user);
        $request->session()->regenerate();
        
        // PERBAIKAN: Cek role dengan cara yang lebih sederhana
        $userRoles = $user->roles->pluck('name')->toArray();
        
        if (in_array('admin', $userRoles)) {
            return redirect('/admin');
        } else {
            return redirect('/dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
