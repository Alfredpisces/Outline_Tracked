@php
    $isActive = fn(string $pattern) => request()->routeIs($pattern);
    $safeRoute = function (string $name, array $params = []) {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $params) : '#';
    };
    $linkBase =
        'group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-semibold transition-all duration-300 relative overflow-hidden border border-transparent';
    $iconBase = 'h-5 w-5 shrink-0 transition-transform duration-300 group-hover:scale-110';
    $inactive = 'text-slate-400 hover:text-white hover:bg-white/5';
    $sectionTitle =
        'px-4 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-3 mt-6 flex items-center gap-3';
@endphp

<aside
    class="w-72 bg-[#0B1120] text-white min-h-screen flex flex-col border-r border-slate-800/60 shadow-2xl relative shrink-0">

    <div
        class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-indigo-500/10 to-transparent pointer-events-none">
    </div>

    <!-- Branding Header -->
    <div class="relative z-10 flex items-center h-24 px-6 border-b border-slate-800/60 shrink-0">
        <a href="{{ $safeRoute('school_head.dashboard') }}" class="flex items-center gap-4 group">

            <!-- TRACKED SVG LOGO -->
            <div
                class="flex items-center justify-center w-11 h-11 bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-500 rounded-xl shadow-lg shadow-indigo-500/20 group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-300 ring-1 ring-white/10 p-2">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="text-2xl font-black text-white tracking-tight leading-none">
                    Track<span class="text-indigo-400">Ed</span>
                </span>
                <span class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-1">
                    Executive Portal
                </span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="relative z-10 flex-1 px-4 py-6 overflow-y-auto space-y-2 custom-scrollbar">

        @php
            $activeDash =
                'bg-gradient-to-r from-indigo-500/10 to-transparent text-indigo-300 border-indigo-500/20 shadow-[inset_0px_0px_20px_rgba(99,102,241,0.05)]';
        @endphp
        <a href="{{ $safeRoute('school_head.dashboard') }}"
            class="{{ $linkBase }} {{ $isActive('school_head.dashboard') ? $activeDash : $inactive }}">
            <svg class="{{ $iconBase }} {{ $isActive('school_head.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-300' }}"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 12l8.25-8.25L20.25 12v8.25A2.25 2.25 0 0118 22.5h-3.75v-6.75H9.75v6.75H6A2.25 2.25 0 013.75 20.25V12z" />
            </svg>
            <span>Command Center</span>
        </a>

        <!-- Behavioral Oversight -->
        <div class="{{ $sectionTitle }}">
            <span>Behavioral Oversight</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>

        @php
            $activeBeh =
                'bg-gradient-to-r from-violet-500/10 to-transparent text-violet-300 border-violet-500/20 shadow-[inset_0px_0px_20px_rgba(139,92,246,0.05)]';
            $activeCasesCount = class_exists(\App\Models\IncidentReport::class)
                ? \App\Models\IncidentReport::where('status', '!=', 'resolved')->count()
                : 0;
        @endphp

        <div class="space-y-1">
            <a href="{{ $safeRoute('school_head.behavior.analytics') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.behavior.analytics') ? $activeBeh : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.behavior.analytics') ? 'text-violet-400' : 'text-slate-500 group-hover:text-violet-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l3-3 4 4 5-6" />
                </svg>
                <span>Behavioral Analytics</span>
            </a>

            <a href="{{ $safeRoute('school_head.behavior.cases') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.behavior.cases') ? $activeBeh : $inactive }} justify-between">
                <div class="flex items-center gap-3">
                    <svg class="{{ $iconBase }} {{ $isActive('school_head.behavior.cases') ? 'text-violet-400' : 'text-slate-500 group-hover:text-violet-300' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                    </svg>
                    <span>Master Case Logs</span>
                </div>
                @if ($activeCasesCount > 0)
                    <span
                        class="px-2 py-0.5 text-[10px] font-black bg-rose-500 text-white rounded-full shadow-lg shadow-rose-500/30">
                        {{ $activeCasesCount }}
                    </span>
                @endif
            </a>

            <a href="{{ $safeRoute('school_head.behavior.good_moral') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.behavior.good_moral') ? $activeBeh : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.behavior.good_moral') ? 'text-violet-400' : 'text-slate-500 group-hover:text-violet-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Good Moral Audits</span>
            </a>
        </div>

        <!-- Academic & Operations -->
        <div class="{{ $sectionTitle }}">
            <span>Academic & Operations</span>
            <div class="h-px bg-slate-800 flex-1"></div>
        </div>

        @php
            $activeOps =
                'bg-gradient-to-r from-slate-700/30 to-transparent text-slate-200 border-slate-700/50 shadow-[inset_0px_0px_20px_rgba(51,65,85,0.2)]';
        @endphp

        <div class="space-y-1">
            <a href="{{ $safeRoute('school_head.dll.index') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.dll.*') ? $activeOps : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.dll.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>DLL Approvals</span>
            </a>
            <a href="{{ $safeRoute('school_head.cot.index') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.cot.*') ? $activeOps : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.cot.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span>Classroom Observations</span>
            </a>
            <!-- Example Sidebar Link Update -->
            <a href="{{ $safeRoute('school_head.dss.index') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.dss.*') ? $activeOps : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.dss.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
                <span>Decision Support</span> <!-- CHANGED HERE -->
            </a>
            <a href="{{ $safeRoute('school_head.inventory.index') }}"
                class="{{ $linkBase }} {{ $isActive('school_head.inventory.*') ? $activeOps : $inactive }}">
                <svg class="{{ $iconBase }} {{ $isActive('school_head.inventory.*') ? 'text-slate-300' : 'text-slate-500 group-hover:text-slate-300' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Inventory Clearances</span>
            </a>
        </div>
    </nav>

    <!-- Footer Profile -->
    <div class="p-5 border-t border-slate-800/60 bg-[#070B15] shrink-0">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-indigo-500/20 ring-1 ring-indigo-500/30 flex items-center justify-center text-sm font-black text-indigo-300">
                {{ strtoupper(substr(Auth::user()->first_name ?? 'S', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? 'School' }}
                    {{ Auth::user()->last_name ?? 'Head' }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Principal</p>
            </div>
        </div>
    </div>
</aside>
