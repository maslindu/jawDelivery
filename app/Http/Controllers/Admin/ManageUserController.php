<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManageUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(12);
        $roles = Role::all();
        return view('admin.manage-users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|max:255',
            'fullName' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:500',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = new User();
            $user->username = $request->username;
            $user->fullName = $request->fullName;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->address = $request->address;

            if ($request->hasFile('avatar')) {
                $avatarName = time() . '.' . $request->avatar->extension();
                $request->avatar->storeAs('public/avatar', $avatarName);
                $user->avatar_link = $avatarName;
            }

            $user->save();
            
            // PERBAIKAN: Gunakan cara yang sama seperti di AuthController
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $user->roles()->attach($role->id);
                
                // Log untuk debugging
                Log::info('User created via admin panel', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role_attached' => $role->name,
                    'role_id' => $role->id
                ]);
            } else {
                Log::error('Role not found when creating user', ['role_name' => $request->role]);
                return redirect()->back()->with('error', 'Role tidak ditemukan!');
            }

            return redirect()->back()->with('success', 'User berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            Log::error('Error creating user via admin panel', [
                'error' => $e->getMessage(),
                'username' => $request->username
            ]);
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => ['required', 'max:255', Rule::unique('users')->ignore($user->id)],
            'fullName' => 'required|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:500',
            'role' => 'required|exists:roles,name',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user->username = $request->username;
            $user->fullName = $request->fullName;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($user->avatar_link && $user->avatar_link !== 'default-avatar.jpg') {
                    Storage::delete('public/avatar/' . $user->avatar_link);
                }
                
                $avatarName = time() . '.' . $request->avatar->extension();
                $request->avatar->storeAs('public/avatar', $avatarName);
                $user->avatar_link = $avatarName;
            }

            $user->save();
            
            // PERBAIKAN: Gunakan cara yang konsisten
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                // Detach semua role lama dan attach role baru
                $user->roles()->detach();
                $user->roles()->attach($role->id);
                
                Log::info('User updated via admin panel', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'new_role' => $role->name
                ]);
            }

            return redirect()->back()->with('success', 'User berhasil diperbarui!');
            
        } catch (\Exception $e) {
            Log::error('Error updating user via admin panel', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting current user
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        
        try {
            // Delete avatar file
            if ($user->avatar_link && $user->avatar_link !== 'default-avatar.jpg') {
                Storage::delete('public/avatar/' . $user->avatar_link);
            }
            
            $user->delete();
            
            Log::info('User deleted via admin panel', [
                'deleted_user_id' => $id,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'User berhasil dihapus!');
            
        } catch (\Exception $e) {
            Log::error('Error deleting user via admin panel', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            Log::error('Error fetching user data', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }
}
