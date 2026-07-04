<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-800 leading-tight">
                    {{ __('Administrator Workspace') }}
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Manage system infrastructure and personnel access.</p>
            </div>
            <div
                class="flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-indigo-700 text-sm font-bold border border-indigo-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System Online
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <a href="{{ route('admin.personnel.index') }}"
                class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500 transition-all group-hover:w-2"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold tracking-widest text-emerald-500 uppercase mb-1">Personnel</p>
                        <h3 class="text-2xl font-black text-slate-800 group-hover:text-emerald-600 transition-colors">
                            Manage Access</h3>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-emerald-50 text-emerald-500 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500 font-medium">Provision accounts and assign PBAC permissions.</p>
                <div
                    class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-emerald-500">
                    <span>Active Accounts: <span
                            class="text-slate-700 group-hover:text-emerald-600">{{ $activeUsersCount ?? '0' }}</span></span>
                    <span>&rarr;</span>
                </div>
            </a>

            <a href="{{ route('admin.lis_sync.index') }}"
                class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-fuchsia-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-fuchsia-500 transition-all group-hover:w-2"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold tracking-widest text-fuchsia-500 uppercase mb-1">Database</p>
                        <h3 class="text-2xl font-black text-slate-800 group-hover:text-fuchsia-600 transition-colors">
                            LIS Sync</h3>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-fuchsia-50 text-fuchsia-500 group-hover:scale-110 group-hover:bg-fuchsia-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500 font-medium">Upload DepEd masterlists and upsert learner records.
                </p>
                <div
                    class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-fuchsia-500">
                    <span>Last Sync: <span
                            class="text-slate-700 group-hover:text-fuchsia-600">{{ $lastSyncDate ?? 'Pending' }}</span></span>
                    <span>&rarr;</span>
                </div>
            </a>

            <a href="{{ route('admin.inventory.index') }}"
                class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-500 transition-all group-hover:w-2"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold tracking-widest text-amber-500 uppercase mb-1">Assets</p>
                        <h3 class="text-2xl font-black text-slate-800 group-hover:text-amber-600 transition-colors">
                            Inventory</h3>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-amber-50 text-amber-500 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500 font-medium">Assign E-PAR hardware and track ICS accountability.
                </p>
                <div
                    class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-amber-500">
                    <span>Unliquidated: <span
                            class="text-slate-700 group-hover:text-amber-600">{{ $unliquidatedAssetsCount ?? '0' }}
                            Items</span></span>
                    <span>&rarr;</span>
                </div>
            </a>

            <a href="{{ route('admin.clearances.index') }}"
                class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-rose-500 transition-all group-hover:w-2"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold tracking-widest text-rose-500 uppercase mb-1">Audits</p>
                        <h3 class="text-2xl font-black text-slate-800 group-hover:text-rose-600 transition-colors">
                            Clearances</h3>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-rose-50 text-rose-500 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.965 11.965 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500 font-medium">Monitor and manage blocked year-end property
                    clearances.</p>
                <div
                    class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 group-hover:text-rose-500">
                    <span>Blocked: <span class="text-rose-600 font-black">{{ $blockedClearancesCount ?? '0' }}
                            Personnel</span></span>
                    <span>&rarr;</span>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:col-span-1">
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.personnel.index') }}#add"
                        class="flex items-center gap-3 w-full p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors text-sm font-semibold text-slate-700">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        Provision New Account
                    </a>
                    <a href="{{ route('admin.inventory.index') }}#assign"
                        class="flex items-center gap-3 w-full p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors text-sm font-semibold text-slate-700">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        Issue New E-PAR
                    </a>
                    <a href="{{ route('admin.lis_sync.index') }}"
                        class="flex items-center gap-3 w-full p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors text-sm font-semibold text-slate-700">
                        <div
                            class="w-8 h-8 rounded-lg bg-fuchsia-50 text-fuchsia-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        Run Database Sync
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:col-span-2">
                <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    System Status & Recent Activity
                </h3>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <div class="flex items-center justify-center h-32">
                        <div class="text-center">
                            <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p class="text-sm font-medium text-slate-500">Activity logs will appear here once the
                                system is active.</p>
                            <p class="text-xs text-slate-400 mt-1">Waiting for initial LIS sync and personnel
                                onboarding...</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
