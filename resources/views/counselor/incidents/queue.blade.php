<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="flex items-center justify-center w-11 h-11 text-xl font-black text-white bg-gradient-to-br from-blue-500 via-indigo-600 to-violet-700 rounded-xl shadow-lg shadow-blue-500/20">
                    📥
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900">
                        Active Intake Queue
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500 font-medium">Triage public QR tips and manage official
                        teacher referrals for intervention.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/40 min-h-[calc(100vh-12rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-8">

            {{-- ========================================== --}}
            {{-- STREAM 1: PUBLIC QR TRIAGE (External)      --}}
            {{-- ========================================== --}}

            {{-- NEW: Success Message for the Kill Switch --}}
            @if (session('success'))
                <div
                    class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div
                    class="p-6 border-b border-rose-100 bg-gradient-to-r from-rose-50 to-white flex flex-wrap gap-4 justify-between items-center">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-rose-600 mb-1">Public Sensor Stream
                        </h3>
                        <h2 class="text-lg font-bold text-slate-800">Unverified QR Tips</h2>
                    </div>
                    <div class="flex items-center gap-3">

                        {{-- THE NEW KILL SWITCH BUTTON --}}
                        @php $intakeEnabled = cache('public_intake_enabled', true); @endphp
                        <form action="{{ route('counselor.tips.toggle') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to {{ $intakeEnabled ? 'disable' : 'enable' }} the public QR link?');"
                                class="inline-flex items-center gap-1.5 {{ $intakeEnabled ? 'bg-white border-slate-200 text-slate-600 hover:text-rose-700 hover:border-rose-300' : 'bg-rose-600 text-white border-rose-600 hover:bg-slate-900' }} border text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                {{ $intakeEnabled ? 'Terminate QR Link' : 'Activate QR Link' }}
                            </button>
                        </form>

                        <a href="{{ route('counselor.qr.print') }}" target="_blank"
                            class="inline-flex items-center gap-1.5 bg-white border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 text-slate-600 hover:text-indigo-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm transition">
                            Print QR Flyer
                        </a>
                        <span class="bg-rose-100 text-rose-700 text-xs font-black px-3 py-1 rounded-full shadow-sm">
                            {{ $unverifiedTips->count() }} Pending
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">Timestamp</th>
                                <th class="px-6 py-4">Raw Report (Tip)</th>
                                <th class="px-6 py-4">Evidence</th>
                                <th class="px-6 py-4 text-center">Source</th>
                                <th class="px-6 py-4 text-right">Triage Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($unverifiedTips as $tip)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($tip->created_at)->format('M d, Y - g:i A') }}
                                    </td>

                                    <td class="px-6 py-4 text-slate-700 max-w-sm">
                                        <span
                                            class="font-black text-slate-900 block mb-1 flex items-center gap-1.5 text-xs uppercase tracking-wider">
                                            {{ $tip->incident_location }}
                                        </span>
                                        <p class="text-xs text-slate-500 truncate" title="{{ $tip->narrative }}">
                                            "{{ Str::limit($tip->narrative, 65) }}"
                                        </p>
                                    </td>

                                    {{-- NEW: Alpine.js Mobile Tap-to-Reveal Logic --}}
                                    <td class="px-6 py-4">
                                        @if ($tip->evidence_path)
                                            <div x-data="{ revealed: false }" @click="revealed = !revealed"
                                                class="relative cursor-pointer w-16 h-16 rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                                <img src="{{ asset('storage/' . $tip->evidence_path) }}"
                                                    :class="revealed ? 'blur-none' : 'blur-md'"
                                                    class="w-full h-full object-cover transition-all duration-300">
                                                <div x-show="!revealed"
                                                    class="absolute inset-0 flex items-center justify-center text-center bg-slate-900/50 transition-opacity">
                                                    <span
                                                        class="text-[8px] font-black text-white uppercase tracking-widest px-1">Tap<br>to
                                                        view</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium italic">No photo
                                                attached</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($tip->reporter_name)
                                            <div class="text-xs font-bold text-slate-800">{{ $tip->reporter_name }}
                                            </div>
                                            <div
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                                                {{ $tip->reporter_contact }}</div>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 border border-slate-200 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md shadow-sm">
                                                Anonymous Tip
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        {{-- NEW: Triage Button is now a functional link --}}
                                        <a href="{{ route('counselor.tips.triage', $tip->id) }}"
                                            class="inline-flex items-center gap-2 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 hover:border-rose-300 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-sm transition">
                                            Triage Tip
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <p class="text-sm font-bold text-slate-400">No unverified public tips at this
                                            time.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- STREAM 2: OFFICIAL TEACHER REFERRALS       --}}
            {{-- ========================================== --}}
            <div
                class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden ring-1 ring-indigo-500/10 shadow-indigo-500/5">
                <div
                    class="p-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-indigo-600 mb-1">Official
                            Escalations</h3>
                        <h2 class="text-lg font-bold text-slate-800">Pending Interventions</h2>
                    </div>
                    <span
                        class="bg-indigo-100 text-indigo-700 text-xs font-black px-3 py-1 rounded-full">{{ $pendingReferrals->count() }}
                        Pending</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">Date Logged</th>
                                <th class="px-6 py-4">Target Student</th>
                                <th class="px-6 py-4">Behavioral Offense</th>
                                <th class="px-6 py-4 text-center">Severity</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingReferrals as $referral)
                                @php
                                    // Detect if this is a group incident based on the controller logic
                                    $isGroupIncident = Str::startsWith($referral->narrative, '[GROUP INCIDENT]');

                                    // Clean the narrative for display by removing the prefix tag
                                    $cleanNarrative = $isGroupIncident
                                        ? trim(str_replace('[GROUP INCIDENT]', '', $referral->narrative))
                                        : $referral->narrative;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($referral->created_at)->format('M d, Y - g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $referral->student->last_name ?? 'Unknown' }},
                                        {{ $referral->student->first_name ?? '' }}
                                        <br>
                                        <span class="text-[10px] font-medium text-slate-400">
                                            Grade {{ $referral->student->grade_level ?? '' }} -
                                            {{ $referral->student->section ?? '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold">{{ $referral->offense_type }}</span>

                                            {{-- The Group Incident Badge --}}
                                            @if ($isGroupIncident)
                                                <span
                                                    class="inline-flex items-center gap-1 bg-fuchsia-50 text-fuchsia-700 border border-fuchsia-200 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md shadow-sm"
                                                    title="Multiple students were reported in this single event">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                    </svg>
                                                    Linked Case
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 truncate max-w-xs"
                                            title="{{ $cleanNarrative }}">
                                            "{{ Str::limit($cleanNarrative, 45) }}"
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest
                                            {{ $referral->severity === 'minor' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $referral->severity === 'major' ? 'bg-orange-100 text-orange-800' : '' }}
                                            {{ $referral->severity === 'critical' ? 'bg-rose-100 text-rose-800 ring-1 ring-rose-500' : '' }}">
                                            {{ $referral->severity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- This links to your beautifully designed edit.blade.php --}}
                                        <a href="{{ route('counselor.incidents.edit', $referral->id) }}"
                                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-sm transition">
                                            Review Case
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-400">No official teacher referrals
                                                pending review.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
