<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
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
            ]);
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


            $role = Role::where('name', RoleEnum::PELANGGAN->value)->first();
            $user->roles()->attach($role->id);
            auth()->login($user);
            return redirect("/dashboard");

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'login_error' => 'An unexpected error occurred. Please try again.'
            ]);
        }

    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return redirect()->back()->withErrors([
                    'login_error' => 'Invalid username or password.'
                ]);
            }
            auth()->login($user);
            if ($user->hasRole('admin')) {
                return redirect('/admin');
            } elseif ($user->hasRole('pelanggan')) {
                return redirect('/dashboard');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'login_error' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }



    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}

