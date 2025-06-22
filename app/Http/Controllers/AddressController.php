<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $addresses = UserAddress::where('user_id', $user->id)
            ->orderByDesc('is_primary')
            ->get();
        return view('address', compact('addresses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $user = Auth::user();

        $hasAddress = UserAddress::where('user_id', $user->id)->exists();

        if ($hasAddress && $request->boolean('is_primary')) {
            UserAddress::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $isPrimary = !$hasAddress || $request->boolean('is_primary');

        UserAddress::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'label' => $request->label,
            'is_primary' => $isPrimary,
        ]);

        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        if ($request->boolean('is_primary')) {
            UserAddress::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $address->update([
            'address' => $request->address,
            'label' => $request->label,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        $address->delete();

        return redirect()->back();
    }
}
