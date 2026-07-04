<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\User;
use App\Models\AssetTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    /**
     * Display a listing of the inventory.
     */
    public function index(Request $request): View
    {
        $query = Inventory::with('assignee')->latest();

        // Optional filtering by category or condition
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $inventories = $query->paginate(15);

        // Identify items that need restocking (Consumables below reorder level)
        $lowStockAlerts = Inventory::where('lifespan_type', 'Consumable')
            ->whereNotNull('reorder_level')
            ->whereRaw('quantity <= reorder_level')
            ->get();

        return view('admin.inventory.index', compact('inventories', 'lowStockAlerts'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create(): View
    {
        // Fetch all teachers/staff to populate the "Assigned To" dropdown
        $users = User::whereIn('role', ['teacher', 'admin', 'counselor', 'school_head'])
                     ->orderBy('last_name')
                     ->get();

        return view('admin.inventory.create', compact('users'));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_number' => ['nullable', 'string', 'max:255', 'unique:inventories'],
            'item_name'       => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'max:255'],
            'lifespan_type'   => ['required', 'in:Consumable,Semi-Expendable,Non-Expendable'],
            'condition'       => ['required', 'in:Serviceable,Needs Minor Repair,Needs Major Repair,Unserviceable,Missing'],
            'quantity'        => ['required', 'integer', 'min:0'],
            'unit'            => ['required', 'string', 'max:50'],
            'reorder_level'   => ['nullable', 'integer', 'min:0'],
            'assignment_type' => ['required', 'in:Personal,Facility'],
            'location'        => ['nullable', 'string', 'max:255'],
            'assigned_to'     => ['nullable', 'exists:users,id'],
            'funding_source'  => ['nullable', 'in:MOOE,SEF / LGU,PTA,Donation,National / DepEd Central'],
            'date_acquired'   => ['nullable', 'date'],
            'photo'           => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('inventory_photos', 'public');
        }

        $inventory = Inventory::create($validated);

        // Automated Audit Trail: Initial Stock Entry
        AssetTransaction::create([
            'inventory_id' => $inventory->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Added Stock',
            'quantity'     => $inventory->quantity,
            'status'       => 'Completed',
            'notes'        => 'Initial inventory registration by Property Custodian.',
        ]);

        return redirect()->route('admin.inventory.index')
                         ->with('success', 'Asset successfully added to the master inventory.');
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Inventory $inventory): View
    {
        $users = User::whereIn('role', ['teacher', 'admin', 'counselor', 'school_head'])
                     ->orderBy('last_name')
                     ->get();

        return view('admin.inventory.edit', compact('inventory', 'users'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'property_number' => ['nullable', 'string', 'max:255', 'unique:inventories,property_number,' . $inventory->id],
            'item_name'       => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'max:255'],
            'lifespan_type'   => ['required', 'in:Consumable,Semi-Expendable,Non-Expendable'],
            'condition'       => ['required', 'in:Serviceable,Needs Minor Repair,Needs Major Repair,Unserviceable,Missing'],
            'quantity'        => ['required', 'integer', 'min:0'],
            'unit'            => ['required', 'string', 'max:50'],
            'reorder_level'   => ['nullable', 'integer', 'min:0'],
            'assignment_type' => ['required', 'in:Personal,Facility'],
            'location'        => ['nullable', 'string', 'max:255'],
            'assigned_to'     => ['nullable', 'exists:users,id'],
            'funding_source'  => ['nullable', 'in:MOOE,SEF / LGU,PTA,Donation,National / DepEd Central'],
            'date_acquired'   => ['nullable', 'date'],
            'photo'           => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle File Upload & Cleanup old photo
        if ($request->hasFile('photo')) {
            if ($inventory->photo_path) {
                Storage::disk('public')->delete($inventory->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('inventory_photos', 'public');
        }

        // Check if condition changed to trigger Automated Audit Trail
        $oldCondition = $inventory->condition;
        $newCondition = $validated['condition'];

        $inventory->update($validated);

        if ($oldCondition !== $newCondition) {
            AssetTransaction::create([
                'inventory_id' => $inventory->id,
                'user_id'      => Auth::id(),
                'action_type'  => 'Condition Change',
                'quantity'     => $inventory->quantity,
                'status'       => 'Completed',
                'notes'        => "Condition updated from {$oldCondition} to {$newCondition}.",
            ]);
        }

        return redirect()->route('admin.inventory.index')
                         ->with('success', 'Asset details updated successfully.');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Inventory $inventory)
    {
        if ($inventory->photo_path) {
            Storage::disk('public')->delete($inventory->photo_path);
        }

        $inventory->delete();

        return redirect()->route('admin.inventory.index')
                         ->with('success', 'Asset permanently removed from inventory.');
    }
}
