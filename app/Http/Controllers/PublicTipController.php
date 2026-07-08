<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicTip;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PublicTipController extends Controller
{
    public function create(): View
    {
        // THE KILL SWITCH CHECK
        if (!cache('public_intake_enabled', true)) {
            abort(403, 'The school administration has temporarily disabled the public intake form.');
        }

        return view('public.report-tip');
    }

    public function store(Request $request): RedirectResponse
    {
        // THE KILL SWITCH CHECK
        if (!cache('public_intake_enabled', true)) {
            abort(403, 'The school administration has temporarily disabled the public intake form.');
        }
        $validated = $request->validate([
            'is_anonymous'      => 'nullable|boolean',
            'reporter_name'     => 'nullable|string|max:255',
            'reporter_contact'  => 'nullable|string|max:255',
            'incident_location' => 'required|string|max:255',
            'narrative'         => 'required|string|min:10',
            'evidence_photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB limit
            'video_link'        => 'nullable|url|max:255',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence_photo')) {
            $evidencePath = $request->file('evidence_photo')->store('public_tips', 'public');
        }

        // Enforce anonymity if the box was checked
        if ($request->filled('is_anonymous')) {
            $validated['reporter_name'] = null;
            $validated['reporter_contact'] = null;
        }

        PublicTip::create([
            'reporter_name'     => $validated['reporter_name'] ?? null,
            'reporter_contact'  => $validated['reporter_contact'] ?? null,
            'incident_location' => $validated['incident_location'],
            'narrative'         => $validated['narrative'],
            'evidence_path'     => $evidencePath,
            'video_link'        => $validated['video_link'] ?? null,
            'status'            => 'unverified',
        ]);

        return redirect()->route('public.tip.create')
            ->with('success', 'Your report has been securely submitted. Thank you for helping keep the campus safe.');
    }
}
