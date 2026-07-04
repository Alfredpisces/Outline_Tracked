<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-500 rounded-xl shadow-lg shadow-indigo-500/20">
                        TE
                    </div>
                    {{ __('Principal Command Center') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Central executive hub for academic approvals, behavioral oversight, and inventory clearance.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('school_head.behavior.analytics') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                    Behavior Analytics →
                </a>

                <a href="{{ route('school_head.reports.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-black uppercase tracking-widest text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Report Generator
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-10rem)] bg-slate-50/50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Quick Start / Notice --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-900">Priority Actions</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Use these shortcuts to jump directly into high-impact executive tasks.
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <!-- Directs exactly to the escalated cases filter -->
                            <a href="{{ route('school_head.behavior.cases', ['status' => 'escalated']) }}"
                                class="inline-flex items-center rounded-2xl bg-rose-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-rose-700 shadow-sm transition hover:bg-rose-600 hover:text-white border border-rose-200 focus:outline-none focus:ring-2 focus:ring-rose-500">
                                <span class="mr-2">🚨</span> Review Escalations
                            </a>

                            <a href="{{ route('school_head.dll.index') }}"
                                class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                                <span class="mr-2">📘</span> Pending DLL Approvals
                            </a>

                            <a href="{{ route('school_head.inventory.index') }}"
                                class="inline-flex items-center rounded-2xl bg-amber-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-amber-700 shadow-sm transition hover:bg-amber-500 hover:text-white border border-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <span class="mr-2">📦</span> Inventory Blockers
                            </a>
                        </div>
                    </div>

                    <div class="w-full lg:w-auto">
                        <div class="rounded-2xl border border-indigo-100 bg-indigo-50/50 p-5 lg:max-w-xs">
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                                <span>⚡</span> System Note
                            </div>
                            <p class="mt-2 text-sm font-bold text-slate-700">
                                Dynamic Reporting Active
                            </p>
                            <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                Analytics and reporting are now driven by flexible date ranges instead of fixed school
                                years. Click into any module to set custom audit parameters.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Main Modules --}}
            <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                {{-- DLL Review --}}
                <a href="{{ route('school_head.dll.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Approvals
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">DLL Review</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Approve or return AI pre-checked lesson plans.
                            </p>
                        </div>

                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-slate-50 text-slate-600 border border-slate-100 flex items-center justify-center font-black">
                            DL
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-indigo-600">Open Module →</span>
                    </div>
                </a>

                {{-- DSS Analytics --}}
                <a href="{{ route('school_head.dss.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Performance
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">DSS Analytics</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Objective teacher rankings and evaluation metrics.
                            </p>
                        </div>

                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-violet-50 text-violet-700 border border-violet-100 flex items-center justify-center font-black">
                            DS
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-violet-600">Open Module →</span>
                    </div>
                </a>

                {{-- Behavioral Oversight --}}
                <a href="{{ route('school_head.behavior.analytics') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Child Protection
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Behavioral Audit</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Monitor major incidents and counselor resolutions.
                            </p>
                        </div>

                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center font-black">
                            BH
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-rose-600">Open Module →</span>
                    </div>
                </a>

                {{-- Inventory Oversight --}}
                <a href="{{ route('school_head.inventory.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Accountability
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Asset Oversight</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Track unliquidated properties and block clearances.
                            </p>
                        </div>

                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center font-black">
                            IV
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-amber-600">Open Module →</span>
                    </div>
                </a>
            </section>

            {{-- Official Reports --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">Institutional Reports</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Generate tamper-proof PDF summary reports for administrative filing.
                        </p>
                    </div>

                    <a href="{{ route('school_head.reports.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-900 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 shrink-0">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        PDF Report Generator
                    </a>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
