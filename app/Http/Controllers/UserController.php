<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username'  => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => [
                'required',
                'regex:/^\+?[0-9]{10,15}$/',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'fullName'  => ['required', 'string', 'max:255'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->username  = $validated['username'];
        $user->fullName  = $validated['fullName'];
        $user->email     = $validated['email'];
        $user->phone     = $validated['phone'];

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $filename = uniqid() . '.' . $avatarFile->getClientOriginalExtension();
            Log::info('Generated filename: ' . $filename);

            $avatarDir = storage_path('app/public/avatar');
            if (!File::exists($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true);
            }

            if ($user->avatar_link && Storage::exists('public/avatar/' . $user->avatar_link)) {
                Storage::delete('public/avatar/' . $user->avatar_link);
            }

            try {
                $path = $avatarFile->storeAs('public/avatar', $filename);
            } catch (\Exception $e) {
                Log::error('Error storing file: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menyimpan avatar: ' . $e->getMessage());
            }

        }

        $user->save();
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
