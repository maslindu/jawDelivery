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
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'              => 'required|string|unique:users,username|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            // password_confirmation field is required
        ]);

        if ($validator->fails()) {
            return $this->responseError('Validasi gagal.', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
            $user = User::create([
                'username' => $validatedData['username'],
                'email'    => $validatedData['email'],
                'password' => $validatedData['password'],
            ]);

            $role = Role::where('name', RoleEnum::PELANGGAN->value)->first();
            $user->roles()->attach($role->id);

            return $this->responseSuccess('Berhasil membuat akun pengguna.', [
                'user' => $user,
                'access_token' => $user->createToken($request->email)->plainTextToken
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error('Signup error: ' . $e->getMessage());
            return $this->responseError('Gagal membuat akun pengguna.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return redirect("/"); 
    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'login_error' => 'An unexpected error occurred. Please try again.'
            ]);
        }
    }
    


    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->responseSuccess(
                    'Berhasil Logout', 
                    null,
                    Response::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::error('An error occurred during login.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return $this->responseError(
                    'An unexpected error occurred. Please try again later.', 
                    null,
                    Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
}

