<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-900 tracking-tight flex items-center gap-3">
                <div
                    class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                </div>
                {{ __('Guidance Command Center') }}
            </h2>
            <span
                class="text-sm font-bold text-slate-500 bg-white border border-slate-200 shadow-sm px-5 py-2 rounded-full uppercase tracking-widest hidden sm:block">
                {{ now()->format('M j, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- NEW: HIGH PRIORITY ACTION BANNER --}}
            @if ($unverifiedTipsCount > 0)
                <div
                    class="bg-rose-50 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 border border-rose-200">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-rose-200 text-rose-600 rounded-2xl animate-pulse">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-rose-900 tracking-tight">Action Required: Unverified
                                Public Tips</h3>
                            <p class="text-rose-700 text-sm font-medium mt-0.5">There are {{ $unverifiedTipsCount }} new
                                reports submitted via the campus QR codes waiting for triage.</p>
                        </div>
                    </div>
                    <a href="{{ route('counselor.incidents.queue') }}"
                        class="w-full sm:w-auto text-center px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-colors shadow-sm">
                        Open Intake Queue
                    </a>
                </div>
            @endif

            {{-- Snapshot Metrics --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex items-center transition duration-300 hover:shadow-md hover:border-blue-200">
                    <div class="p-4 rounded-2xl bg-blue-500 text-white mr-5 shadow-lg shadow-blue-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Escalations</p>
                        <p class="text-4xl font-black text-slate-900 mt-1">{{ $pendingIncidentsCount }}</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex items-center transition duration-300 hover:shadow-md hover:border-violet-200">
                    <div
                        class="p-4 rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 text-white mr-5 shadow-lg shadow-violet-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Violator Profiles</p>
                        <p class="text-4xl font-black text-slate-900 mt-1">{{ $violatorsCount }}</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex items-center transition duration-300 hover:shadow-md hover:border-emerald-200">
                    <div
                        class="p-4 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white mr-5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Clearances Issued</p>
                        <p class="text-4xl font-black text-slate-900 mt-1">{{ $certificatesCount }}</p>
                    </div>
                </div>
            </div>

            {{-- 2-Column Split: Work Queue & Analytics --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- LEFT: Quick-Access Work Queue --}}
                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Recent Escalations
                        </h3>
                        <a href="{{ route('counselor.incidents.queue') }}"
                            class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors">View
                            All</a>
                    </div>
                    <div class="p-0 flex-1 overflow-y-auto">
                        @if ($recentPendingCases->isEmpty())
                            <div
                                class="flex flex-col items-center justify-center text-center p-12 h-full text-slate-400">
                                <svg class="w-10 h-10 mb-3 opacity-50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-bold">Inbox Zero!</p>
                                <p class="text-xs font-medium mt-1">No pending teacher referrals.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-slate-100">
                                @foreach ($recentPendingCases as $case)
                                    <li
                                        class="p-5 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-black text-slate-900 truncate">
                                                {{ $case->student->last_name }}, {{ $case->student->first_name }}</p>
                                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ $case->offense_type }}
                                            </p>
                                        </div>
                                        <a href="{{ route('counselor.incidents.edit', $case->id) }}"
                                            class="ml-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white border border-indigo-100">
                                            Review
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- RIGHT: Upgraded Analytics --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
                    <h3 class="text-lg font-black text-slate-800 mb-6">Severity Distribution</h3>

                    @if ($totalIncidents === 0)
                        <div class="flex flex-col items-center justify-center text-center py-10">
                            <div class="p-5 bg-slate-50 rounded-full mb-4 shadow-inner border border-slate-100 z-10">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-500 max-w-sm mt-2 z-10">Analytics will
                                automatically generate once the first disciplinary incidents are logged.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            {{-- Minor --}}
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2 uppercase tracking-widest">
                                    <span class="text-amber-600">Minor ({{ $minorCount }})</span>
                                    <span class="text-slate-600">{{ $minorPercentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-amber-400 h-3 rounded-full transition-all duration-1000"
                                        style="width: {{ $minorPercentage }}%"></div>
                                </div>
                            </div>

                            {{-- Major --}}
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2 uppercase tracking-widest">
                                    <span class="text-rose-600">Major ({{ $majorCount }})</span>
                                    <span class="text-slate-600">{{ $majorPercentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-rose-500 h-3 rounded-full transition-all duration-1000"
                                        style="width: {{ $majorPercentage }}%"></div>
                                </div>
                            </div>

                            {{-- Critical (NEW) --}}
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2 uppercase tracking-widest">
                                    <span class="text-purple-600">Critical / Legal ({{ $criticalCount }})</span>
                                    <span class="text-slate-600">{{ $criticalPercentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-purple-600 h-3 rounded-full transition-all duration-1000"
                                        style="width: {{ $criticalPercentage }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Master
                                Records</span>
                            <span class="text-xl font-black text-slate-900">{{ $totalIncidents }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Links Bar --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('counselor.incidents.create') }}"
                    class="group flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-rose-300 hover:shadow-md transition">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">Log Manual Intake</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-rose-500 group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>

                <a href="{{ route('counselor.violators.index') }}"
                    class="group flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-violet-300 hover:shadow-md transition">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-violet-50 text-violet-600 rounded-lg group-hover:bg-violet-600 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">Search Behavioral DB</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-violet-500 group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>

                <a href="{{ route('counselor.good_moral.index') }}"
                    class="group flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">Issue Good Moral</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
