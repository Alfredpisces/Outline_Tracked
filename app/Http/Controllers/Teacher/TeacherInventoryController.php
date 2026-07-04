<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\AssetTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherInventoryController extends Controller
{
    /**
     * Display the inventory assigned to the authenticated teacher.
     */
    public function index(Request $request): View
    {
        $teacherId = Auth::id();

        $inventories = Inventory::where('assigned_to', $teacherId)->latest()->get();
        $personalItems = $inventories->where('assignment_type', 'Personal');
        $facilityItems = $inventories->where('assignment_type', 'Facility');

        return view('teacher.inventory.index', compact('personalItems', 'facilityItems'));
    }

    /**
     * Show form to report damage for an item.
     */
    public function reportDamage(Inventory $inventory): View
    {
        // Security check: Ensure the teacher is actually assigned to this item
        abort_if($inventory->assigned_to !== Auth::id(), 403, 'Unauthorized access.');

        return view('teacher.inventory.damage', compact('inventory'));
    }

    /**
     * Store the damage report.
     */
    public function storeDamage(Request $request, Inventory $inventory)
    {
        abort_if($inventory->assigned_to !== Auth::id(), 403);

        $validated = $request->validate([
            'condition' => ['required', 'in:Needs Minor Repair,Needs Major Repair,Unserviceable,Missing'],
            'notes'     => ['required', 'string'],
            'photo'     => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('inventory_photos', 'public');
        }

        // 1. Update the master inventory condition
        $inventory->update(['condition' => $validated['condition']]);

        // 2. Log the transaction for the Custodian to see
        AssetTransaction::create([
            'inventory_id' => $inventory->id,
            'user_id'      => Auth::id(),
            'action_type'  => 'Condition Change',
            'quantity'     => 1,
            'status'       => 'Completed',
            'notes'        => $validated['notes'],
            'photo_path'   => $photoPath,
        ]);

        return redirect()->route('teacher.inventory.index')
                         ->with('success', 'Damage report submitted to the Property Custodian.');
    }

    /**
     * Show form to log student borrowing.
     */
    public function logBorrow(Inventory $inventory): View
    {
        abort_if($inventory->assigned_to !== Auth::id(), 403);

        return view('teacher.inventory.borrow', compact('inventory'));
    }

    /**
     * Store the borrow transaction.
     */
    public function storeBorrow(Request $request, Inventory $inventory)
    {
        abort_if($inventory->assigned_to !== Auth::id(), 403);

        $validated = $request->validate([
            'borrower_name'        => ['required', 'string', 'max:255'],
            'action_type'          => ['required', 'in:Borrowed,Consumed'],
            'quantity'             => ['required', 'integer', 'min:1', 'max:' . $inventory->quantity],
            'expected_return_date' => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
        ]);

        // If it's a consumable, deduct it permanently from the master inventory
        if ($validated['action_type'] === 'Consumed') {
            $inventory->decrement('quantity', $validated['quantity']);
        }

        AssetTransaction::create([
            'inventory_id'         => $inventory->id,
            'user_id'              => Auth::id(),
            'action_type'          => $validated['action_type'],
            'quantity'             => $validated['quantity'],
            'borrower_name'        => $validated['borrower_name'],
            'borrower_type'        => 'Student',
            'borrowed_at'          => now(),
            'expected_return_date' => $validated['expected_return_date'],
            'status'               => $validated['action_type'] === 'Consumed' ? 'Completed' : 'Active',
            'notes'                => $validated['notes'],
        ]);

        return redirect()->route('teacher.inventory.index')
                         ->with('success', 'Transaction successfully logged.');
    }
}
