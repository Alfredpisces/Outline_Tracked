<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PortfolioController extends Controller
{
    /**
     * Display the teacher's portfolio dashboard.
     */
    public function index(Request $request): View
    {
        // Fetch only the logged-in teacher's achievements, newest first
        $achievements = PortfolioItem::where('user_id', Auth::id())
            ->orderBy('date_earned', 'desc')
            ->get();

        return view('teacher.portfolio.index', compact('achievements'));
    }

    /**
     * Show the form for creating a new portfolio item.
     */
    public function create(): View
    {
        return view('teacher.portfolio.create');
    }

    /**
     * Store a newly created portfolio item in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|string|max:100',
            'date_earned' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:1000',
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB limit
        ]);

        // Handle the file upload securely
        $filePath = null;
        if ($request->hasFile('certificate_file')) {
            // Save it in the 'public/portfolio_vault' folder
            $filePath = $request->file('certificate_file')->store('portfolio_vault', 'public');
        }

        // Create the database record
        PortfolioItem::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'category' => $request->category,
            'level' => $request->level,
            'date_earned' => $request->date_earned,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        return redirect()->route('teacher.portfolio.index')
                         ->with('status', 'Achievement successfully added to your vault!');
    }

    /**
     * Remove the specified portfolio item and its file from storage.
     */
    public function destroy(PortfolioItem $portfolio): RedirectResponse
    {
        // Security Check: Ensure the teacher only deletes their own records
        if ($portfolio->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the physical file from the hard drive to save space
        if ($portfolio->file_path && Storage::disk('public')->exists($portfolio->file_path)) {
            Storage::disk('public')->delete($portfolio->file_path);
        }

        // Delete the database record
        $portfolio->delete();

        return redirect()->route('teacher.portfolio.index')
                         ->with('status', 'Achievement record permanently deleted.');
    }
}
