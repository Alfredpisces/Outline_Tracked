<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl shadow-md">
                        🧠
                    </div>
                    AI Decision Support
                </h2>
                <p class="mt-1 text-sm text-slate-500">AI-Powered analytics for faculty compliance, behavioral
                    violations, and asset life cycle.</p>
            </div>

            <div class="flex items-center gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Filter:</span>
                    <select name="sy" onchange="this.form.submit()"
                        class="text-sm font-bold text-slate-700 bg-white border-slate-200 rounded-xl focus:ring-indigo-500 shadow-sm py-2 px-4">
                        <option value="2026-2027" {{ $activeSY == '2026-2027' ? 'selected' : '' }}>SY 2026-2027</option>
                        <option value="2025-2026" {{ $activeSY == '2025-2026' ? 'selected' : '' }}>SY 2025-2026</option>
                    </select>
                </form>
            </div>
        </div>
    </x-slot>

    {{-- x-data controls the modal state --}}
    <div x-data="{ showModal: false, activeContext: '' }" class="py-10 min-h-[calc(100vh-10rem)] bg-slate-50/50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- DOMINANT MODULE: OUTSTANDING FACULTY RANKING --}}
            <section class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div
                    class="p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-indigo-600 mb-1">Primary Metric
                        </h3>
                        <h2 class="text-xl font-bold text-slate-800">Outstanding Faculty Compliance Leaderboard</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Objective ranking based on Daily Lesson Log approval
                            rates for Outstanding recognition.</p>
                    </div>
                    {{-- NEW BUTTON REPLACING THE BADGE --}}
                    <div class="hidden sm:block text-right">
                        <button
                            @click="showModal = true; activeContext = 'faculty'; $dispatch('set-context', { context: 'faculty' })"
                            class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Analyze & Justify Rankings
                        </button>
                    </div>
                </div>

                <div class="p-6 bg-indigo-50/30 border-b border-slate-100">
                    <p class="text-sm text-slate-700 leading-relaxed">
                        <strong class="font-black text-indigo-900">AI Technical Assistance Insight:</strong>
                        {{ $aiFacultySummary ?? 'Gathering compliance data to generate technical assistance recommendations...' }}
                    </p>
                    {{-- THE OLD BUTTON HERE HAS BEEN DELETED --}}
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-6 py-4">Rank</th>
                                <th class="px-6 py-4">Faculty Member</th>
                                <th class="px-6 py-4 text-center">Total Logs</th>
                                <th class="px-6 py-4 text-center">Approved</th>
                                <th class="px-6 py-4 text-center">Returned</th>
                                <th class="px-6 py-4 text-right">Compliance Score</th>
                                <th class="px-6 py-4 text-center">DSS Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($facultyRankings as $index => $teacher)
                                <tr
                                    class="hover:bg-slate-50 transition-colors {{ $teacher->compliance_rate >= 90 ? 'bg-emerald-50/20' : '' }}">
                                    <td
                                        class="px-6 py-4 font-black {{ $index < 3 ? 'text-amber-500 text-lg' : 'text-slate-400' }}">
                                        @if ($index === 0)
                                            🥇
                                        @elseif($index === 1)
                                            🥈
                                        @elseif($index === 2)
                                            🥉
                                        @else
                                            #{{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $teacher->name }}</td>
                                    <td class="px-6 py-4 text-center font-medium text-slate-600">{{ $teacher->total }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-emerald-600">
                                        {{ $teacher->checked }}</td>
                                    <td class="px-6 py-4 text-center font-medium text-rose-600">
                                        {{ $teacher->needs_revision }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-lg font-black {{ $teacher->compliance_rate >= 90 ? 'text-emerald-600' : ($teacher->compliance_rate >= 75 ? 'text-amber-500' : 'text-rose-500') }}">
                                            {{ $teacher->compliance_rate }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($teacher->compliance_rate >= 90)
                                            <span
                                                class="bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md">Outstanding</span>
                                        @elseif($teacher->needs_revision >= 3)
                                            <span
                                                class="bg-rose-100 text-rose-800 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md">Needs
                                                TA</span>
                                        @else
                                            <span class="text-slate-400 text-xs font-medium">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-500 text-sm">No lesson
                                        logs have been submitted for this school year.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- SECONDARY MODULES: AI EXECUTIVE SUMMARIES --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- BEHAVIORAL ANALYTICS SUMMARY --}}
                <section class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 bg-amber-50/50">
                        <h3 class="text-xs font-black uppercase tracking-widest text-amber-600 mb-1">Predictive Security
                        </h3>
                        <h2 class="text-lg font-bold text-slate-800">Behavioral Analytics Summary</h2>
                    </div>
                    <div class="p-6 flex-1 flex flex-col gap-4 justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-600 text-xs">✨</span>
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-500">AI Executive
                                    Brief</h4>
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed italic border-l-2 border-amber-300 pl-4">
                                "{{ $aiBehaviorSummary ?? 'Processing violation records to map behavioral hotspots and recommend security deployments...' }}"
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            {{-- NEW TRIGGER BUTTON --}}
                            <button
                                @click="showModal = true; activeContext = 'behavior'; $dispatch('set-context', { context: 'behavior' })"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Analyze Insights & Take Action
                            </button>
                        </div>
                    </div>
                </section>

                {{-- ASSET LIFE CYCLE SUMMARY --}}
                <section class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 bg-emerald-50/50">
                        <h3 class="text-xs font-black uppercase tracking-widest text-emerald-600 mb-1">Data-Driven
                            Budgeting</h3>
                        <h2 class="text-lg font-bold text-slate-800">Asset Life Cycle & Procurement</h2>
                    </div>
                    <div class="p-6 flex-1 flex flex-col gap-4 justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 text-xs">✨</span>
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-500">AI Executive
                                    Brief</h4>
                            </div>
                            <p class="text-sm text-slate-700 leading-relaxed italic border-l-2 border-emerald-300 pl-4">
                                "{{ $aiAssetSummary ?? 'Analyzing asset acquisition dates and repair logs to determine upcoming condemnation and procurement requirements...' }}"
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            {{-- NEW TRIGGER BUTTON --}}
                            <button
                                @click="showModal = true; activeContext = 'asset'; $dispatch('set-context', { context: 'asset' })"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Analyze Insights & Take Action
                            </button>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        {{-- ========================================== --}}
        {{-- THE TRANSPARENT ACTION MODAL OVERLAY       --}}
        {{-- ========================================== --}}
        <div x-show="showModal" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
            style="display: none;">

            <div @click.away="showModal = false" x-show="showModal" x-transition.scale.origin.bottom
                class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl flex flex-col overflow-hidden">

                {{-- MODAL HEADER & CLOSE BUTTON --}}
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="text-indigo-600 text-xl">🤖</span> AI Insight Analyst
                    </h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- THE SAFETY DISCLAIMER (The Shield) --}}
                <div class="px-6 py-3 bg-amber-50 border-b border-amber-100 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-[11px] text-amber-800 leading-tight">
                        <strong class="font-black uppercase tracking-wider">System Notice:</strong> This analysis is
                        generated by AI based strictly on currently available database logs. It may not capture
                        unrecorded offline context. This tool is designed to support, not replace, final administrative
                        judgment.
                    </p>
                </div>

                {{-- LIVEWIRE COMPONENT INJECTION --}}
                <div class="p-0">
                    <livewire:dss-action-desk :faculty-rankings="$facultyRankings" :behavior-summary="$aiBehaviorSummary ?? ''" :asset-summary="$aiAssetSummary ?? ''" />
                </div>

            </div>
        </div>
        {{-- END MODAL --}}

    </div>
</x-app-layout>
