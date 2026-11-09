<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminInventoryController extends Controller
{
    /**
     * Display the Master Inventory dashboard and Proactive Action Center.
     */
    public function index()
    {
        // 1. Fetch the main inventory list with pagination and eager load the assignee
        $inventories = Inventory::with('assignee')->latest()->paginate(15);

        // 2. Calculate Low Stock Alerts (Quantity <= Reorder Level)
        $lowStockAlerts = Inventory::whereNotNull('reorder_level')
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->get();

        // 3. Calculate Expiring / End-of-Life Alerts safely
        // If the 'expiry_date' column doesn't exist in MySQL, it safely returns an empty collection instead of crashing
        $expiringAlerts = Schema::hasColumn('inventories', 'expiry_date')
            ? Inventory::whereNotNull('expiry_date')
                ->where('expiry_date', '<=', Carbon::now()->addDays(90))
                ->orderBy('expiry_date', 'asc')
                ->get()
            : collect();

        return view('admin.inventory.index', compact('inventories', 'lowStockAlerts', 'expiringAlerts'));
    }

    /**
     * Show the form for manually creating a new asset.
     */
    public function create()
    {
        // Fetch users to populate the "Assigned To" dropdown (Teachers, Staff, etc.)
        $users = User::orderBy('last_name')->get();

        return view('admin.inventory.create', compact('users'));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'property_number' => 'nullable|string|unique:inventories,property_number|max:255',
            'lifespan_type' => 'required|in:Non-Expendable,Semi-Expendable,Consumable',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'condition' => 'required|in:Serviceable,Needs Minor Repair,Needs Major Repair,Unserviceable,Missing',
            'reorder_level' => 'nullable|integer|min:0',
            'assignment_type' => 'required|in:Facility,Personal',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'funding_source' => 'nullable|string|max:255',
            'date_acquired' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048', // Max 2MB image
        ]);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('inventory_photos', 'public');
        }

        // Remove the 'photo' key from the array as we use 'photo_path' in the DB
        unset($validated['photo']);

        // Remove expiry_date if column is not in DB to prevent insert error
        if (!Schema::hasColumn('inventories', 'expiry_date')) {
            unset($validated['expiry_date']);
        }

        Inventory::create($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'New asset successfully registered to the Master Inventory.');
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Inventory $inventory)
    {
        $users = User::orderBy('last_name')->get();
        return view('admin.inventory.edit', compact('inventory', 'users'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'property_number' => 'nullable|string|max:255|unique:inventories,property_number,' . $inventory->id,
            'lifespan_type' => 'required|in:Non-Expendable,Semi-Expendable,Consumable',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'condition' => 'required|in:Serviceable,Needs Minor Repair,Needs Major Repair,Unserviceable,Missing',
            'reorder_level' => 'nullable|integer|min:0',
            'assignment_type' => 'required|in:Facility,Personal',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'funding_source' => 'nullable|string|max:255',
            'date_acquired' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle Photo Upload & Deletion of old photo
        if ($request->hasFile('photo')) {
            if ($inventory->photo_path) {
                Storage::disk('public')->delete($inventory->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('inventory_photos', 'public');
        }

        unset($validated['photo']);

        if (!Schema::hasColumn('inventories', 'expiry_date')) {
            unset($validated['expiry_date']);
        }

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Asset details have been successfully updated.');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Inventory $inventory)
    {
        // Delete the photo from storage if it exists
        if ($inventory->photo_path) {
            Storage::disk('public')->delete($inventory->photo_path);
        }

        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Asset has been permanently removed from the registry.');
    }
}
