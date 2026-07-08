@php
    $isActive = fn(string $pattern) => request()->routeIs($pattern);
    $activeRole = session('active_role', Auth::user()->role ?? 'counselor');
    $linkBase =
        'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 relative overflow-hidden border border-transparent';
    $iconBase = 'h-5 w-5 shrink-0 transition-transform duration-300 group-hover:scale-110';
    $inactive = 'text-slate-300/80 hover:text-white hover:bg-white/5';
    $sectionTitle = 'px-3 text-[11px] font-bold tracking-widest text-slate-500 uppercase mb-2 flex items-center gap-2';
@endphp

<aside class="w-72 bg-slate-900 text-white min-h-screen flex flex-col border-r border-slate-800 shadow-xl relative">
    <div
        class="absolute top-0 left-0 w-full h-48 bg-gradient-to-b from-indigo-600/20 to-transparent pointer-events-none">
    </div>

    {{-- Brand --}}
    <div class="relative z-10 flex items-center h-20 px-6 border-b border-slate-800/50 shrink-0 mt-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">

            <div
                class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-600 rounded-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-300 ring-1 ring-white/20 p-2">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="text-xl font-black text-white tracking-tight leading-none">
                    Track<span class="text-indigo-300">Ed</span>
                </span>
                <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">
                    Counselor Workspace
                </span>
            </div>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="relative z-10 flex-1 px-4 py-6 overflow-y-auto space-y-8 custom-scrollbar">

        {{-- Dashboard --}}
        <div class="space-y-1">
            @php
                $activeDash =
                    'bg-gradient-to-r from-indigo-500/20 to-transparent text-indigo-200 border-indigo-500/40 shadow-[inset_0px_0px_18px_rgba(99,102,241,0.14)]';
            @endphp
            <a href="{{ route('dashboard') }}"
                class="{{ $linkBase }} {{ $isActive('dashboard') ? $activeDash : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('dashboard') ? 'text-indigo-300' : 'text-slate-500 group-hover:text-indigo-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12l8.25-8.25L20.25 12v8.25A2.25 2.25 0 0118 22.5h-3.75v-6.75H9.75v6.75H6A2.25 2.25 0 013.75 20.25V12z" />
                </svg>
                <span>Counselor Dashboard</span>
            </a>
        </div>

        @if (in_array($activeRole, ['counselor', 'admin']))
            {{-- 1. ACTION REQUIRED: INTAKE DESK --}}
            <div>
                <div class="{{ $sectionTitle }}">
                    <div class="h-px bg-slate-800 flex-1"></div>
                    <span class="text-blue-400">Action Required</span>
                    <div class="h-px bg-slate-800 flex-1"></div>
                </div>
                <div class="space-y-1.5">
                    @php
                        $activeQueue =
                            'bg-gradient-to-r from-blue-500/20 to-transparent text-blue-200 border-blue-500/40 shadow-[inset_0px_0px_18px_rgba(59,130,246,0.14)]';
                    @endphp
                    <a href="{{ route('counselor.incidents.queue') }}"
                        class="{{ $linkBase }} {{ $isActive('counselor.incidents.queue') ? $activeQueue : $inactive }}">
                        <svg class="{{ $iconBase }} {{ $isActive('counselor.incidents.queue') ? 'text-blue-300' : 'text-slate-500 group-hover:text-blue-300' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span>Active Intake Queue</span>
                    </a>
                </div>
            </div>

            {{-- 2. CASE MANAGEMENT --}}
            <div>
                <div class="{{ $sectionTitle }}">
                    <div class="h-px bg-slate-800 flex-1"></div>
                    <span>Case Management</span>
                    <div class="h-px bg-slate-800 flex-1"></div>
                </div>
                <div class="space-y-1.5">
                    @php
                        $activeLog =
                            'bg-gradient-to-r from-rose-500/20 to-transparent text-rose-200 border-rose-500/40 shadow-[inset_0px_0px_18px_rgba(244,63,94,0.14)]';
                        $activeIndex =
                            'bg-gradient-to-r from-emerald-500/20 to-transparent text-emerald-200 border-emerald-500/40 shadow-[inset_0px_0px_18px_rgba(16,185,129,0.14)]';
                    @endphp
                    <a href="{{ route('counselor.incidents.create') }}"
                        class="{{ $linkBase }} {{ $isActive('counselor.incidents.create') ? $activeLog : $inactive }}">
                        <svg class="{{ $iconBase }} {{ $isActive('counselor.incidents.create') ? 'text-rose-300' : 'text-slate-500 group-hover:text-rose-300' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Manual Intake Form</span>
                    </a>
                    <a href="{{ route('counselor.incidents.index') }}"
                        class="{{ $linkBase }} {{ $isActive('counselor.incidents.index') ? $activeIndex : $inactive }}">
                        <svg class="{{ $iconBase }} {{ $isActive('counselor.incidents.index') ? 'text-emerald-300' : 'text-slate-500 group-hover:text-emerald-300' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                        </svg>
                        <span>Incident History</span>
                    </a>
                </div>
            </div>

            {{-- Student Records --}}
            <div>
                <div class="{{ $sectionTitle }}">
                    <div class="h-px bg-slate-800 flex-1"></div>
                    <span>Student Records</span>
                    <div class="h-px bg-slate-800 flex-1"></div>
                </div>
                @php
                    $activeVio =
                        'bg-gradient-to-r from-violet-500/20 to-transparent text-violet-200 border-violet-500/40 shadow-[inset_0px_0px_18px_rgba(139,92,246,0.14)]';
                @endphp
                <a href="{{ route('counselor.violators.index') }}"
                    class="{{ $linkBase }} {{ $isActive('counselor.violators.*') ? $activeVio : $inactive }}">
                    <svg class="{{ $iconBase }} {{ $isActive('counselor.violators.*') ? 'text-violet-300' : 'text-slate-500 group-hover:text-violet-300' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                    </svg>
                    <span>Behavioral Database</span>
                </a>
            </div>

            {{-- Clearance --}}
            <div>
                <div class="{{ $sectionTitle }}">
                    <div class="h-px bg-slate-800 flex-1"></div>
                    <span>Clearance</span>
                    <div class="h-px bg-slate-800 flex-1"></div>
                </div>
                @php
                    $activeGM =
                        'bg-gradient-to-r from-emerald-500/20 to-transparent text-emerald-200 border-emerald-500/40 shadow-[inset_0px_0px_18px_rgba(16,185,129,0.14)]';
                @endphp
                <a href="{{ route('counselor.good_moral.index') }}"
                    class="{{ $linkBase }} {{ $isActive('counselor.good_moral.*') ? $activeGM : $inactive }}">
                    <svg class="{{ $iconBase }} {{ $isActive('counselor.good_moral.*') ? 'text-emerald-300' : 'text-slate-500 group-hover:text-emerald-300' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Good Moral Certificates</span>
                </a>
            </div>
        @endif
    </nav>

    {{-- Footer --}}
    <div class="p-4 border-t border-slate-800/50 bg-slate-900">
        <div class="flex items-center gap-3 px-2">
            <div
                class="w-9 h-9 rounded-2xl bg-slate-800 ring-1 ring-white/10 flex items-center justify-center text-sm font-black">
                {{ strtoupper(substr(Auth::user()->first_name ?? 'C', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? '' }}
                    {{ Auth::user()->last_name ?? '' }}</p>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $activeRole }}</p>
            </div>
        </div>
    </div>
</aside>
