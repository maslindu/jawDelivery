<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'username'  => ['required', 'string', 'max:255', 'regex:/^\S*$/', Rule::unique('users', 'username')->ignore($user->id)],
            'email'     => ['required',  'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => [
                'required',
                'regex:/^\+?[0-9]{10,15}$/',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'fullName'  => ['required', 'string', 'max:255'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);


        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $validated = $validator->validated();
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
                $user->avatar_link = $filename;
            } catch (\Exception $e) {
                Log::error('Error storing file: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menyimpan avatar: ' . $e->getMessage());
            }

        }

        $user->save();
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
