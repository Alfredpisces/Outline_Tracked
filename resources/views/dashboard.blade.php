<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-sky-500/20">
                        TE
                    </div>
                    {{ __('Faculty Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Your personal workspace for academic planning, professional growth, and asset tracking.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ $safeRoute('teacher.dll.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    + Draft New DLL
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-10rem)] bg-slate-50/50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Welcome & System Notice --}}
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-900">Welcome back,
                            {{ Auth::user()->first_name ?? 'Teacher' }}!</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            TrackEd has been updated. Your COT observations are now completely private, and you have a
                            new E-Portfolio vault for your certificates.
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <a href="{{ $safeRoute('teacher.portfolio.index') }}"
                                class="inline-flex items-center rounded-2xl bg-indigo-50 px-5 py-3 text-xs font-black uppercase tracking-widest text-indigo-700 shadow-sm transition hover:bg-indigo-600 hover:text-white border border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <span class="mr-2">🎓</span> Access E-Portfolio
                            </a>

                            <a href="{{ $safeRoute('teacher.cot.index') }}"
                                class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                                <span class="mr-2">📊</span> View COT Analytics
                            </a>
                        </div>
                    </div>

                    <div class="w-full lg:w-auto">
                        <div
                            class="rounded-2xl border border-sky-100 bg-sky-50/50 p-5 lg:min-w-[250px] flex items-center gap-4">
                            <div
                                class="relative w-16 h-16 flex items-center justify-center bg-white rounded-full border-4 border-sky-500 shadow-sm">
                                <span class="text-sm font-black text-sky-700">100%</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sky-500">Current Quarter
                                </p>
                                <p class="mt-1 text-sm font-bold text-slate-700">DLL Compliance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Main Workspaces Grid --}}
            <section class="grid grid-cols-1 gap-6 md:grid-cols-3">

                {{-- Academic Builder --}}
                <a href="{{ $safeRoute('teacher.dll.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Preparation
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Lesson Builder</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Draft Daily Lesson Logs with the AI Pre-Checker.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-xl">
                            📘</div>
                    </div>
                    <div class="mt-6 border-t border-slate-50 pt-4 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-blue-600">Open Module →</span>
                    </div>
                </a>

                {{-- Disciplinary Reporting --}}
                <a href="{{ $safeRoute('teacher.behavior.create') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Child
                                Protection</div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">Log Incident</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Submit behavioral reports to the Guidance Counselor.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-xl">
                            📝</div>
                    </div>
                    <div class="mt-6 border-t border-slate-50 pt-4 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-rose-600">Report Offense →</span>
                    </div>
                </a>

                {{-- Asset Management --}}
                <a href="{{ $safeRoute('teacher.inventory.index') }}"
                    class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Accountability
                            </div>
                            <h3 class="mt-2 text-lg font-extrabold text-slate-900">My Inventory</h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                Sign E-PARs and monitor your property clearance status.
                            </p>
                        </div>
                        <div
                            class="h-12 w-12 shrink-0 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl">
                            📦</div>
                    </div>
                    <div class="mt-6 border-t border-slate-50 pt-4 flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-amber-600">View Assets →</span>
                    </div>
                </a>

            </section>
        </div>
    </div>
</x-app-layout>
