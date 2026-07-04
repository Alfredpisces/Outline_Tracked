<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PersonnelController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'permissions'])->orderBy('last_name')->get();
        $permissions = Permission::orderBy('module_name')->orderBy('action_name')->get();
        $roles = Role::all();

        return view('admin.personnel.index', compact('users', 'permissions', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validation
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id',
            'email'       => 'required|email|unique:users,email',
            'first_name'  => 'required',
            'last_name'   => 'required',
            'password'    => 'required|string|min:8',
            'roles'       => 'required|array',
            'department'  => 'nullable|string', // Added Profiling Field
            'deped_rank'  => 'nullable|string', // Added Profiling Field
        ]);

        // 2. Create User
        $user = User::create([
            'employee_id'       => $request->employee_id,
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'name'              => $request->first_name . ' ' . $request->last_name,
            'email'             => $request->email,
            'is_active'         => $request->boolean('is_active', true),
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),

            // Profiling Additions
            'department'        => $request->department,
            'deped_rank'        => $request->deped_rank,
            'is_verified'       => true,             // Auto-verify since Admin created them
            'verified_by'       => auth()->id(),     // Log which Admin created this account
        ]);

        // 3. Set primary string role fallback
        if (!empty($request->roles)) {
            $primaryRole = Role::find($request->roles[0]);
            if ($primaryRole) {
                $user->role = $primaryRole->name;
                $user->save();
            }
        }

        // 4. Sync Roles
        $user->roles()->sync($request->roles);

        // 5. AUTOMATIC PERMISSION ASSIGNMENT
        $rolesWithPermissions = Role::with('permissions')->whereIn('id', $request->roles)->get();

        $permissionIds = $rolesWithPermissions->flatMap(function ($role) {
            return $role->permissions->pluck('id');
        })->unique()->toArray();

        $user->permissions()->sync($permissionIds);

        return redirect()->route('admin.personnel.index')->with('status', 'Personnel account created and profiled successfully.');
    }

    // =========================================================================
    // THIS IS THE MISSING METHOD THAT FIXES THE ERROR!
    // =========================================================================
    public function edit(User $personnel): View
    {
        $roles = Role::all();
        return view('admin.personnel.edit', compact('personnel', 'roles'));
    }
    // =========================================================================

    public function update(Request $request, User $personnel): RedirectResponse
    {
        // 1. Validation
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,' . $personnel->id,
            'email'       => 'required|email|unique:users,email,' . $personnel->id,
            'first_name'  => 'required',
            'last_name'   => 'required',
            'roles'       => 'required|array',
            'password'    => 'nullable|string|min:8|confirmed', // Added for the reset password feature
            'department'  => 'nullable|string', // Added Profiling Field
            'deped_rank'  => 'nullable|string', // Added Profiling Field
        ]);

        // 2. Prepare Update Data
        $updateData = [
            'employee_id' => $request->employee_id,
            'email'       => $request->email,
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'name'        => $request->first_name . ' ' . $request->last_name,
            'is_active'   => $request->has('is_active') ? $request->boolean('is_active') : $personnel->is_active,
            'department'  => $request->department,
            'deped_rank'  => $request->deped_rank,
        ];

        // Only hash and update the password if the Admin actually typed a new one
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // 3. Execute Update
        $personnel->update($updateData);

        // 4. Set primary string role fallback
        if (!empty($request->roles)) {
            $primaryRole = Role::find($request->roles[0]);
            if ($primaryRole) {
                $personnel->role = $primaryRole->name;
                $personnel->save();
            }
        }

        // 5. Sync Roles
        $personnel->roles()->sync($request->roles);

        // 6. AUTOMATIC PERMISSION ASSIGNMENT
        $rolesWithPermissions = Role::with('permissions')->whereIn('id', $request->roles)->get();

        $permissionIds = $rolesWithPermissions->flatMap(function ($role) {
            return $role->permissions->pluck('id');
        })->unique()->toArray();

        $personnel->permissions()->sync($permissionIds);

        return redirect()->route('admin.personnel.index')->with('status', 'Personnel profile updated securely.');
    }
}
