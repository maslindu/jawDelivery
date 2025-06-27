<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('user')->paginate(10);

        return view('admin.manage-driver', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'fullName' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'vehicle_type' => 'required|string|max:100',
            'license_plate' => 'required|string|max:20|unique:drivers',
            'driver_license' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:50',
            'working_hours' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Create user first
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'fullName' => $request->fullName,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Assign KURIR role to the user
            $user->addRole(RoleEnum::KURIR->value);

            // Create driver profile
            $driver = Driver::create([
                'user_id' => $user->id,
                'vehicle_type' => $request->vehicle_type,
                'license_plate' => $request->license_plate,
                'driver_license' => $request->driver_license,
                'vehicle_registration' => $request->vehicle_registration,
                'working_hours' => $request->working_hours,
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('drivers.index')
                ->with('success', 'Driver berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan driver: ' . $e->getMessage());
        }
    }

    public function show(Driver $driver)
    {
        $driver->load('user');
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $driver->load('user');
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($driver->user_id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($driver->user_id)],
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'vehicle_type' => 'required|string|max:100',
            'license_plate' => ['required', 'string', 'max:20', Rule::unique('drivers')->ignore($driver->id)],
            'driver_license' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,suspended',
            'working_hours' => 'nullable|array',
            'is_available' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Update user information
            $driver->user->update([
                'username' => $request->username,
                'email' => $request->email,
                'fullName' => $request->fullName,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8|confirmed']);
                $driver->user->update(['password' => Hash::make($request->password)]);
            }

            // Update driver information
            $driver->update([
                'vehicle_type' => $request->vehicle_type,
                'license_plate' => $request->license_plate,
                'driver_license' => $request->driver_license,
                'vehicle_registration' => $request->vehicle_registration,
                'status' => $request->status,
                'working_hours' => $request->working_hours,
                'is_available' => $request->boolean('is_available'),
            ]);

            DB::commit();

            return redirect()->route('drivers.index')
                ->with('success', 'Driver berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui driver: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver)
    {
        DB::beginTransaction();

        try {
            $driverName = $driver->user->fullName ?? $driver->user->username;

            // Delete driver (this will also delete the user due to cascade)
            $driver->user->delete();

            DB::commit();

            return redirect()->route('drivers.index')
                ->with('success', "Driver {$driverName} berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus driver: ' . $e->getMessage());
        }
    }

    /**
     * Toggle driver availability status.
     */
    public function toggleAvailability(Driver $driver)
    {
        try {
            $driver->toggleAvailability();

            $status = $driver->is_available ? 'tersedia' : 'tidak tersedia';

            return response()->json([
                'success' => true,
                'message' => "Status driver berhasil diubah menjadi {$status}.",
                'is_available' => $driver->is_available
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active and available drivers (API endpoint).
     */
    public function getAvailableDrivers()
    {
        $drivers = Driver::available()
            ->with('user:id,fullName,username,phone,avatar_link')
            ->get()
            ->map(function ($driver) {
                return $driver->full_info;
            });

        return response()->json([
            'success' => true,
            'data' => $drivers
        ]);
    }

    /**
     * Update driver status.
     */
    public function updateStatus(Request $request, Driver $driver)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        try {
            $driver->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status driver berhasil diperbarui.',
                'status' => $driver->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status driver: ' . $e->getMessage()
            ], 500);
        }
    }
}
