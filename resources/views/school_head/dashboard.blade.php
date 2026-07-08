<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-600 rounded-xl shadow-lg shadow-indigo-500/30">
                        TE
                    </div>
                    {{ __('TrackEd Command Center') }}
                </h2>
                <div class="mt-1 flex items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">AI-Powered</span>
                    <p class="text-sm text-slate-500">
                        Executive hub for academic approvals, behavioral escalations, and asset clearance.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('school_head.behavior.analytics') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition-all hover:bg-violet-700 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
                    Behavior Analytics →
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-10rem)] bg-slate-50/50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- 1. Priority Action Center --}}
            {{-- Improved Priority Action Section --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 relative overflow-hidden">
                <div
                    class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 blur-3xl opacity-50 pointer-events-none">
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start relative z-10">

                    <!-- Left Column: Title and Buttons (Takes up the majority of space) -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Priority Actions
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 max-w-lg">
                            Items requiring immediate executive review or signature.
                        </p>

                        <!-- Fixed: Using flex-wrap and gap to prevent button squashing -->
                        <div class="mt-6 flex flex-wrap gap-3">

                            <!-- Review Escalations Button -->
                            <a href="{{ route('school_head.behavior.cases', ['status' => 'escalated']) }}"
                                class="group relative inline-flex items-center rounded-2xl bg-rose-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-rose-700 shadow-sm transition-all hover:bg-rose-600 hover:text-white border border-rose-200 focus:outline-none">
                                <span class="mr-2">🚨</span> Review Escalations
                                @if (isset($escalatedCasesCount) && $escalatedCasesCount > 0)
                                    <span
                                        class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-[10px] text-white shadow-sm ring-2 ring-white">
                                        {{ $escalatedCasesCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- DLL Approvals Button -->
                            <a href="{{ route('school_head.dll.index') }}"
                                class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition-all hover:bg-slate-800 focus:outline-none">
                                <span class="mr-2">📘</span> Pending DLLs
                                @if (isset($pendingDllCount) && $pendingDllCount > 0)
                                    <span class="ml-2 rounded-full bg-indigo-500 px-2 py-0.5 text-[10px] text-white">
                                        {{ $pendingDllCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- Inventory Blockers Button -->
                            <a href="{{ route('school_head.inventory.index') }}"
                                class="inline-flex items-center rounded-2xl bg-amber-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-amber-700 shadow-sm transition-all hover:bg-amber-500 hover:text-white border border-amber-200 focus:outline-none">
                                <span class="mr-2">📦</span> Inventory Blockers
                                @if (isset($inventoryBlockersCount) && $inventoryBlockersCount > 0)
                                    <span class="ml-2 rounded-full bg-amber-600 px-2 py-0.5 text-[10px] text-white">
                                        {{ $inventoryBlockersCount }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: AI Status Card (Prevent from shrinking) -->
                    <div class="w-full lg:w-80 shrink-0">
                        <div class="rounded-2xl border border-indigo-100 bg-indigo-50/50 p-5 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">AI Engine
                                    Active</span>
                            </div>
                            <h4 class="mt-3 text-sm font-bold text-slate-800">Real-Time Tracking</h4>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                Behavioral pattern recognition and asset lifecycle modules are actively monitoring
                                database inputs.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 2. Core Administration Modules --}}
            <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                {{-- DLL Review --}}
                <a href="{{ route('school_head.dll.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-indigo-500 transition-colors">
                                Academics
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">DLL Review</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                Approve or provide technical assistance on AI pre-checked lesson plans.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- DSS Analytics --}}
                <a href="{{ route('school_head.dss.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:border-violet-200 focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-violet-500 transition-colors">
                                Performance
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">DSS Analytics</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                View objective teacher rankings and AI-generated executive summaries.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Behavioral Oversight --}}
                <a href="{{ route('school_head.behavior.analytics') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:border-rose-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-rose-500 transition-colors">
                                Child Protection
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Behavioral Audit</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                Monitor major violation records and enforce Good Moral clearances.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Inventory Oversight --}}
                <a href="{{ route('school_head.inventory.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg hover:border-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-amber-500 transition-colors">
                                Accountability
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Asset Oversight</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                Approve condemnations and monitor high-consumption supplies.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                        </div>
                    </div>
                </a>
            </section>

            {{-- 3. Institutional Reporting --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Institutional Reports</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Generate tamper-proof summary documents for official administrative filing.
                        </p>
                    </div>

                    <a href="{{ route('school_head.reports.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-50 px-6 py-3 text-sm font-bold tracking-wide text-indigo-700 shadow-sm transition-all hover:bg-indigo-100 border border-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 shrink-0">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Open Report Generator
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
