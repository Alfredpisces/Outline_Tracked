@php
    /**
     * Safe route helper prevents RouteNotFoundException while building modules.
     */
    $safeRoute = function (string $name, array $params = []) {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : '#';
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-12 h-12 text-xl font-black text-white bg-gradient-to-br from-indigo-500 via-blue-600 to-sky-500 rounded-2xl shadow-lg shadow-blue-500/30">
                        TE
                    </div>
                    {{ __('Faculty Dashboard') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-slate-500">
                    TrackEd AI-Powered Workspace • Academic Planning & Asset Accountability
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ $safeRoute('teacher.dll.create') }}"
                    class="group relative inline-flex items-center justify-center overflow-hidden rounded-xl bg-blue-600 px-6 py-3 font-black uppercase tracking-widest text-white text-xs shadow-xl shadow-blue-600/20 transition-all hover:scale-105 hover:bg-blue-700">
                    <span
                        class="absolute inset-0 bg-white/20 opacity-0 transition-opacity group-hover:opacity-100"></span>
                    <span class="mr-2 text-lg leading-none">+</span> Draft New DLL
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-10rem)] bg-slate-50/50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- 1. HERO BANNER --}}
            <section
                class="relative overflow-hidden rounded-[2rem] bg-slate-900 px-8 py-10 shadow-2xl sm:px-12 sm:py-12">
                <!-- Decorative background elements -->
                <div
                    class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none">
                </div>
                <div
                    class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-sky-500/20 blur-3xl pointer-events-none">
                </div>
                <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none">
                    <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-sky-300 backdrop-blur-md mb-4 border border-white/10">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                            </span>
                            System Update Live
                        </span>
                        <h3 class="text-3xl font-black tracking-tight text-white sm:text-4xl">
                            Welcome back, {{ Auth::user()->first_name ?? 'Teacher' }}!
                        </h3>
                        <p class="mt-3 text-sm font-medium leading-relaxed text-slate-300">
                            Your E-Portfolio vault is now active for secure certificate storage. The behavioral tracking
                            module has also been updated to exclusively monitor disciplinary violations for streamlined
                            Child Protection policy alignment.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 shrink-0 mt-4 lg:mt-0">
                        <a href="{{ $safeRoute('teacher.portfolio.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-white/10 px-6 py-3 text-xs font-black uppercase tracking-widest text-white backdrop-blur-md transition-all hover:bg-white/20 border border-white/10 hover:scale-105">
                            <span class="mr-2 text-lg">🎓</span> E-Portfolio
                        </a>
                        <a href="{{ $safeRoute('teacher.cot.index') }}"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-900 shadow-lg transition-all hover:bg-slate-100 hover:scale-105">
                            <span class="mr-2 text-lg">📊</span> COT Analytics
                        </a>
                    </div>
                </div>
            </section>

            {{-- 2. ANALYTICS ROW (Floating Glass Cards) --}}
            <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

                {{-- DLL Status KPI --}}
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 hover:ring-blue-200">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 -translate-y-16 translate-x-16 rounded-full bg-blue-50 opacity-50 transition-transform group-hover:scale-150">
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">Lesson Plans</p>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                            📘</div>
                    </div>
                    <div class="relative z-10 mt-6 flex items-end gap-2">
                        <h4 class="text-4xl font-black tracking-tighter text-slate-900">{{ $pendingDllCount ?? '0' }}
                        </h4>
                        <span class="mb-1 text-xs font-bold text-slate-500 uppercase tracking-wider">Pending</span>
                    </div>
                    <p class="relative z-10 mt-2 text-xs font-medium text-slate-400">Awaiting School Head review.</p>
                </div>

                {{-- Asset Life Cycle KPI --}}
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10 hover:ring-amber-200">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 -translate-y-16 translate-x-16 rounded-full bg-amber-50 opacity-50 transition-transform group-hover:scale-150">
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-amber-500">My Inventory</p>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100 transition-colors group-hover:bg-amber-500 group-hover:text-white">
                            📦</div>
                    </div>
                    <div class="relative z-10 mt-6 flex items-end gap-2">
                        <h4 class="text-4xl font-black tracking-tighter text-slate-900">{{ $activeAssetsCount ?? '0' }}
                        </h4>
                        <span class="mb-1 text-xs font-bold text-slate-500 uppercase tracking-wider">Assigned</span>
                    </div>
                    <p class="relative z-10 mt-2 text-xs font-medium text-slate-400">Active items in accountability.</p>
                </div>

                {{-- Behavioral Tracking KPI --}}
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-rose-500/10 hover:ring-rose-200">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 -translate-y-16 translate-x-16 rounded-full bg-rose-50 opacity-50 transition-transform group-hover:scale-150">
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-rose-500">Child Protection</p>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 transition-colors group-hover:bg-rose-600 group-hover:text-white">
                            📝</div>
                    </div>
                    <div class="relative z-10 mt-6 flex items-end gap-2">
                        <h4 class="text-4xl font-black tracking-tighter text-slate-900">
                            {{ $recentViolationsCount ?? '0' }}</h4>
                        <span class="mb-1 text-xs font-bold text-slate-500 uppercase tracking-wider">Logged</span>
                    </div>
                    <p class="relative z-10 mt-2 text-xs font-medium text-slate-400">Disciplinary violations reported.
                    </p>
                </div>

                {{-- AI Compliance (FIXED DESIGN) --}}
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10 hover:ring-emerald-200">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 -translate-y-16 translate-x-16 rounded-full bg-emerald-50 opacity-50 transition-transform group-hover:scale-150">
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Q1 Compliance</p>
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                            ✨</div>
                    </div>
                    <div class="relative z-10 mt-6 flex items-end gap-2">
                        <h4 class="text-4xl font-black tracking-tighter text-slate-900">{{ $complianceRate ?? '100' }}%
                        </h4>
                        <span class="mb-1 text-xs font-bold text-slate-500 uppercase tracking-wider">Optimal</span>
                    </div>
                    <div class="relative z-10 mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-slate-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        AI Pre-Flight Passed
                    </div>
                </div>
            </section>

            {{-- 3. SPLIT VIEW: Recent Activity & Quick Navigation --}}
            <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Left Column: Recent DLLs --}}
                <div
                    class="lg:col-span-2 flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100">
                    <div
                        class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 p-5 px-6 backdrop-blur-xl">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                📋</div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Recent Lesson Logs
                            </h3>
                        </div>
                        <a href="{{ $safeRoute('teacher.dll.index') }}"
                            class="group flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-blue-600 transition-colors hover:text-blue-800">
                            View All <span class="transition-transform group-hover:translate-x-1">→</span>
                        </a>
                    </div>
                    <div class="flex-1 p-0">
                        <table class="w-full text-left whitespace-nowrap">
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($recentDlls ?? [] as $dll)
                                    <tr class="group transition-colors hover:bg-slate-50/80">
                                        <td class="p-5 px-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="h-10 w-10 shrink-0 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                    {{ substr($dll->subject ?? 'SB', 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="font-extrabold text-slate-900">
                                                        {{ $dll->subject ?? 'Subject Name' }}</p>
                                                    <p
                                                        class="mt-0.5 text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-1">
                                                        <span>📅</span> {{ $dll->teaching_dates ?? 'Dates' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-5 px-6 text-right">
                                            <span
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-600 shadow-sm transition-colors group-hover:border-blue-200 group-hover:bg-blue-50 group-hover:text-blue-700">
                                                {{ $dll->status ?? 'Draft' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="p-12 text-center">
                                            <div
                                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-2xl text-slate-400">
                                                📄</div>
                                            <h4 class="mt-4 text-sm font-bold text-slate-900">No recent lesson plans
                                            </h4>
                                            <p class="mt-1 text-xs text-slate-500">Time to draft your first weekly
                                                matrix.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Right Column: Quick Navigation Modules --}}
                <div class="flex flex-col gap-4">
                    <h3 class="px-2 text-xs font-black uppercase tracking-widest text-slate-400">Quick Modules</h3>

                    {{-- Disciplinary Reporting --}}
                    <a href="{{ $safeRoute('teacher.behavior.create') }}"
                        class="group relative overflow-hidden rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-500/10 hover:ring-rose-200">
                        <div
                            class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-rose-50 opacity-0 transition-opacity group-hover:opacity-100">
                        </div>
                        <div class="relative z-10 flex items-center gap-5">
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-2xl text-rose-600 ring-1 ring-rose-100 transition-transform group-hover:scale-110">
                                📝</div>
                            <div>
                                <h4 class="text-base font-extrabold text-slate-900">Log Violation</h4>
                                <p class="mt-1 text-xs font-medium leading-relaxed text-slate-500">Submit behavior
                                    records directly to the Guidance Counselor.</p>
                            </div>
                        </div>
                    </a>

                    {{-- Asset Management --}}
                    <a href="{{ $safeRoute('teacher.inventory.index') }}"
                        class="group relative overflow-hidden rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200">
                        <div
                            class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-50 opacity-0 transition-opacity group-hover:opacity-100">
                        </div>
                        <div class="relative z-10 flex items-center gap-5">
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-2xl text-amber-600 ring-1 ring-amber-100 transition-transform group-hover:scale-110">
                                📦</div>
                            <div>
                                <h4 class="text-base font-extrabold text-slate-900">Asset Life Cycle</h4>
                                <p class="mt-1 text-xs font-medium leading-relaxed text-slate-500">Manage E-PARs, track
                                    assignments, and report damages.</p>
                            </div>
                        </div>
                    </a>
                </div>

            </section>
        </div>
    </div>
</x-app-layout>
