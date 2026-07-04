@php
    $isActive = fn(string $pattern) => request()->routeIs($pattern);
    $safeRoute = function (string $name, array $params = []) {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : '#';
    };
    $linkBase =
        'group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-300 relative overflow-hidden border border-transparent';
    $iconBase = 'h-5 w-5 shrink-0 transition-transform duration-300 group-hover:scale-110';
    $inactive = 'text-slate-400 hover:text-white hover:bg-white/5';
    $sectionTitle =
        'px-4 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2 mt-6 flex items-center gap-3';
@endphp

<aside
    class="w-72 bg-[#0B1120] text-white min-h-screen flex flex-col border-r border-slate-800/60 shadow-2xl relative shrink-0">

    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-sky-500/10 to-transparent pointer-events-none">
    </div>

    <div class="relative z-10 flex items-center h-24 px-6 border-b border-slate-800/60 shrink-0">
        <a href="{{ $safeRoute('teacher.dashboard') }}" class="flex items-center gap-4 group">

            <!-- TRACKED SVG LOGO -->
            <div
                class="flex items-center justify-center w-11 h-11 bg-gradient-to-br from-sky-400 via-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-sky-500/20 group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-300 ring-1 ring-white/10 p-2">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="text-2xl font-black text-white tracking-tight leading-none">
                    Track<span class="text-sky-400">Ed</span>
                </span>
                <span class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-1">
                    Faculty Portal
                </span>
            </div>
        </a>
    </div>

    <nav class="relative z-10 flex-1 px-3 py-6 overflow-y-auto space-y-1 custom-scrollbar">

        {{-- Dashboard --}}
        @php
            $activeDash =
                'bg-gradient-to-r from-sky-500/10 to-transparent text-sky-300 border-sky-500/20 shadow-[inset_0px_0px_20px_rgba(14,165,233,0.05)]';
        @endphp
        <a href="{{ $safeRoute('teacher.dashboard') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.dashboard') ? $activeDash : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.dashboard') ? 'text-sky-400' : 'text-slate-500 group-hover:text-sky-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 12l8.25-8.25L20.25 12v8.25A2.25 2.25 0 0118 22.5h-3.75v-6.75H9.75v6.75H6A2.25 2.25 0 013.75 20.25V12z" />
            </svg>
            <span>My Dashboard</span>
        </a>

        {{-- Academic Work --}}
        <div class="{{ $sectionTitle }}">
            <span>Academic Work</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>
        @php
            $activeDll =
                'bg-gradient-to-r from-blue-500/10 to-transparent text-blue-300 border-blue-500/20 shadow-[inset_0px_0px_20px_rgba(59,130,246,0.05)]';
        @endphp
        <a href="{{ $safeRoute('teacher.dll.index') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.dll.*') ? $activeDll : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.dll.*') ? 'text-blue-400' : 'text-slate-500 group-hover:text-blue-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Daily Lesson Logs</span>
        </a>

        {{-- Professional Growth --}}
        <div class="{{ $sectionTitle }}">
            <span>Professional Growth</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>
        @php
            $activeGrowth =
                'bg-gradient-to-r from-indigo-500/10 to-transparent text-indigo-300 border-indigo-500/20 shadow-[inset_0px_0px_20px_rgba(99,102,241,0.05)]';
        @endphp
        <a href="{{ $safeRoute('teacher.cot.index') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.cot.*') ? $activeGrowth : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.cot.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span>COT Analytics (Private)</span>
        </a>
        <a href="{{ $safeRoute('teacher.portfolio.index') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.portfolio.*') ? $activeGrowth : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.portfolio.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
            </svg>
            <span>E-Portfolio Vault</span>
        </a>

        {{-- Discipline Reporting --}}
        <div class="{{ $sectionTitle }}">
            <span>Discipline Reporting</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>
        @php
            $activeInc =
                'bg-gradient-to-r from-rose-500/10 to-transparent text-rose-300 border-rose-500/20 shadow-[inset_0px_0px_20px_rgba(244,63,94,0.05)]';
        @endphp
        <a href="{{ $safeRoute('teacher.behavior.create') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.behavior.create') ? $activeInc : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.behavior.create') ? 'text-rose-400' : 'text-slate-500 group-hover:text-rose-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Log Incident</span>
        </a>
        <a href="{{ $safeRoute('teacher.behavior.index') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.behavior.index') ? $activeInc : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.behavior.index') ? 'text-rose-400' : 'text-slate-500 group-hover:text-rose-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>My Submitted Reports</span>
        </a>

        {{-- Asset Management --}}
        <div class="{{ $sectionTitle }}">
            <span>Asset Management</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>
        @php
            $activeInv =
                'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-300 border-amber-500/20 shadow-[inset_0px_0px_20px_rgba(245,158,11,0.05)]';
        @endphp
        <a href="{{ $safeRoute('teacher.inventory.index') }}"
            class="{{ $linkBase }} {{ $isActive('teacher.inventory.*') ? $activeInv : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('teacher.inventory.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-amber-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span>My Assigned Inventory</span>
        </a>
    </nav>

    <div class="p-5 border-t border-slate-800/60 bg-[#070B15] shrink-0">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-sky-500/20 ring-1 ring-sky-500/30 flex items-center justify-center text-sm font-black text-sky-300">
                {{ strtoupper(substr(Auth::user()->first_name ?? 'T', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? 'Faculty' }}
                    {{ Auth::user()->last_name ?? 'Member' }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Teacher</p>
            </div>
        </div>
    </div>
</aside>
