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
                {{ __('Guidance Counselor Workspace') }}
            </h2>
            <span
                class="text-sm font-bold text-slate-500 bg-white border border-slate-200 shadow-sm px-5 py-2 rounded-full uppercase tracking-widest">
                {{ now()->format('M j, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Overview Snapshot</h3>

                    @if ($totalIncidents === 0)
                        <span
                            class="flex items-center text-[10px] font-black tracking-widest text-indigo-600 uppercase bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-full animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span> Awaiting Data
                        </span>
                    @else
                        <span
                            class="flex items-center text-[10px] font-black tracking-widest text-emerald-600 uppercase bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Database Synced
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex items-center transition duration-300 hover:shadow-md hover:border-rose-200">
                        <div
                            class="p-4 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 text-white mr-5 shadow-lg shadow-rose-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Incidents</p>
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
            </div>

            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-6">Core Modules</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <a href="{{ route('counselor.incidents.index') }}"
                        class="group block rounded-3xl bg-white p-8 shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-rose-300 relative overflow-hidden z-10">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-rose-50 to-white rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125">
                        </div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="p-3 bg-white shadow-sm border border-rose-100 text-rose-500 rounded-xl group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <svg class="w-6 h-6 text-slate-300 group-hover:text-rose-500 transition-all transform group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-slate-900 text-xl">Incident Review</h3>
                        <p class="mt-2 text-sm text-slate-500 font-medium">Review teacher submissions, log
                            interventions, and resolve cases.</p>
                    </a>

                    <a href="{{ route('counselor.violators.index') }}"
                        class="group block rounded-3xl bg-white p-8 shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-violet-300 relative overflow-hidden z-10">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-violet-50 to-white rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125">
                        </div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="p-3 bg-white shadow-sm border border-violet-100 text-violet-500 rounded-xl group-hover:bg-violet-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <svg class="w-6 h-6 text-slate-300 group-hover:text-violet-600 transition-all transform group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-slate-900 text-xl">Violator Records</h3>
                        <p class="mt-2 text-sm text-slate-500 font-medium">Access the targeted database of student
                            behavioral histories.</p>
                    </a>

                    <a href="{{ route('counselor.good_moral.index') }}"
                        class="group block rounded-3xl bg-white p-8 shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-emerald-300 relative overflow-hidden z-10">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-emerald-50 to-white rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125">
                        </div>
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="p-3 bg-white shadow-sm border border-emerald-100 text-emerald-500 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <svg class="w-6 h-6 text-slate-300 group-hover:text-emerald-500 transition-all transform group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-slate-900 text-xl">Good Moral</h3>
                        <p class="mt-2 text-sm text-slate-500 font-medium">Verify eligibility and generate clearance
                            certificates.</p>
                    </a>
                </div>
            </div>

            <div class="mt-10">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-6">Behavioral Analytics</h3>

                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 md:p-10 relative overflow-hidden">
                    @if ($totalIncidents === 0)
                        <div class="flex flex-col items-center justify-center text-center py-10">
                            <div
                                class="absolute -top-16 -left-16 w-56 h-56 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10">
                            </div>
                            <div
                                class="absolute -bottom-16 -right-16 w-56 h-56 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-10">
                            </div>

                            <div class="p-5 bg-slate-50 rounded-full mb-6 shadow-inner border border-slate-100 z-10">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-2xl font-black text-slate-800 z-10">No Analytics Available Yet</h4>
                            <p class="text-sm font-medium text-slate-500 max-w-lg mt-3 z-10">Violation frequency
                                insights will automatically generate here once the first disciplinary incidents are
                                logged into the system.</p>
                        </div>
                    @else
                        <div>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Total Recorded
                                Incidents: <span class="text-slate-800">{{ $totalIncidents }}</span></h4>

                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between text-sm font-bold mb-2">
                                        <span class="text-amber-600">Minor Offenses ({{ $minorCount }})</span>
                                        <span class="text-slate-600">{{ $minorPercentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden">
                                        <div class="bg-amber-400 h-4 rounded-full transition-all duration-1000"
                                            style="width: {{ $minorPercentage }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm font-bold mb-2">
                                        <span class="text-rose-600">Major Offenses ({{ $majorCount }})</span>
                                        <span class="text-slate-600">{{ $majorPercentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden">
                                        <div class="bg-rose-500 h-4 rounded-full transition-all duration-1000"
                                            style="width: {{ $majorPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs font-bold text-slate-400 mt-8 uppercase tracking-widest text-center">Data
                                updates automatically upon incident resolution.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
