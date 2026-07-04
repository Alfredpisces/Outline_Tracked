<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class SchoolheadInventoryController extends Controller
{
    /**
     * Display the Executive Asset Oversight Dashboard.
     */
    public function index()
    {
        // 1. Total Asset Volume
        $totalAssets = Inventory::count();

        // 2. Infrastructure Health Index Calculations
        $serviceableCount = Inventory::where('condition', 'Serviceable')->count();
        $repairCount = Inventory::whereIn('condition', ['Needs Minor Repair', 'Needs Major Repair'])->count();
        $unserviceableCount = Inventory::where('condition', 'Unserviceable')->count();

        // Calculate percentages safely (avoiding division by zero)
        $healthPercentage = $totalAssets > 0 ? round(($serviceableCount / $totalAssets) * 100) : 0;
        $repairPercentage = $totalAssets > 0 ? round(($repairCount / $totalAssets) * 100) : 0;
        $unserviceablePercentage = $totalAssets > 0 ? round(($unserviceableCount / $totalAssets) * 100) : 0;

        // 3. Accountability Flags
        $missingCount = Inventory::where('condition', 'Missing')->count();

        // 4. Pending Condemnation & Disposal Queue
        // Fetch all non-expendable/semi-expendable items that the Admin marked as Unserviceable
        $pendingDisposals = Inventory::where('condition', 'Unserviceable')
            ->whereIn('lifespan_type', ['Non-Expendable', 'Semi-Expendable'])
            ->latest()
            ->get();

        // 5. Supply Burn Rate Insights
        // Fetch Consumables that have dropped below their reorder threshold
        $highConsumptionItems = Inventory::where('lifespan_type', 'Consumable')
            ->whereNotNull('reorder_level')
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity', 'asc')
            ->take(6) // Limit to top 6 most critical items for the UI card
            ->get();

        return view('school_head.inventory.index', compact(
            'totalAssets',
            'healthPercentage',
            'repairPercentage',
            'unserviceablePercentage',
            'missingCount',
            'pendingDisposals',
            'highConsumptionItems'
        ));
    }

    /**
     * Approve the write-off/disposal of a condemned asset.
     */
    public function approveDisposal(Request $request, $id)
    {
        $asset = Inventory::findOrFail($id);

        // Ensure only unserviceable items are disposed
        if ($asset->condition !== 'Unserviceable') {
            return back()->with('error', 'Only unserviceable items can be written off.');
        }

        // Action: In a real scenario, you might change status to 'Disposed'
        // or utilize SoftDeletes to remove it from active inventory but keep the record.
        $asset->delete(); // Assuming SoftDeletes is on the Inventory model

        // Log the executive action in the transactions table
        $asset->transactions()->create([
            'user_id' => auth()->id(), // School Head
            'action_type' => 'Condition Change',
            'quantity' => $asset->quantity,
            'status' => 'Completed',
            'notes' => 'Executive Approval: Asset officially condemned and written off the registry.'
        ]);

        return back()->with('success', 'Asset successfully approved for disposal and removed from active registry.');
    }

    /**
     * Reject the disposal request (e.g., mandate a repair attempt instead).
     */
    public function rejectDisposal(Request $request, $id)
    {
        $asset = Inventory::findOrFail($id);

        // Revert condition so the Admin has to coordinate repairs
        $asset->update([
            'condition' => 'Needs Major Repair'
        ]);

        $asset->transactions()->create([
            'user_id' => auth()->id(),
            'action_type' => 'Condition Change',
            'quantity' => $asset->quantity,
            'status' => 'Completed',
            'notes' => 'Executive Rejection: Disposal denied. Flagged for mandatory repair attempt.'
        ]);

        return back()->with('success', 'Disposal rejected. The asset has been flagged for repair.');
    }
}
