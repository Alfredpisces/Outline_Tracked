@php
    $isActive = fn(string $pattern) => request()->routeIs($pattern);

    // Check the active workspace session first, then fallback to database role
    $activeRole = session('active_role', Auth::user()->role);

    // We will apply specific colors per item below, so these are just baseline structural classes.
    $linkBase =
        'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition-all duration-300 relative overflow-hidden';
    $iconBase = 'h-5 w-5 shrink-0 transition-transform duration-300 group-hover:scale-110';
    $inactiveBase = 'text-slate-400 bg-transparent hover:bg-white/5';
@endphp

<div class="flex flex-col h-full bg-slate-900 relative">

    <div class="absolute top-0 left-0 w-full h-48 bg-gradient-to-b from-blue-600/20 to-transparent pointer-events-none">
    </div>

    <div class="relative z-10 flex items-center h-20 px-6 border-b border-slate-800/50 shrink-0 mt-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">

            <div
                class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-300 ring-1 ring-white/20 p-2">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>

            <div class="flex flex-col">
                <span class="text-xl font-black text-white tracking-tight leading-none">Track<span
                        class="text-blue-400">Ed</span></span>
                <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">Systems</span>
            </div>
        </a>
    </div>

    <div class="relative z-10 flex-1 px-4 py-6 overflow-y-auto space-y-8 custom-scrollbar">

        <div class="space-y-1">
            @php $activeCyan = 'bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 border-l-4 border-cyan-400 shadow-[inset_0px_0px_20px_rgba(34,211,238,0.1)]'; @endphp
            <a href="{{ route('dashboard') }}"
                class="{{ $linkBase }} {{ $isActive('dashboard') ? $activeCyan : $inactiveBase }} hover:text-cyan-300">
                <svg class="{{ $iconBase }} {{ $isActive('dashboard') ? 'text-cyan-400' : 'text-slate-500 group-hover:text-cyan-400' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 12l8.25-8.25L20.25 12v8.25A2.25 2.25 0 0118 22.5h-3.75v-6.75H9.75v6.75H6A2.25 2.25 0 013.75 20.25V12z" />
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        @if ($activeRole === 'admin')
            <div class="animate-fade-in">
                <div
                    class="px-3 text-[11px] font-bold tracking-widest text-slate-500 uppercase mb-3 flex items-center gap-2">
                    <div class="h-px bg-slate-700 flex-1"></div>
                    <span>Admin Controls</span>
                    <div class="h-px bg-slate-700 flex-1"></div>
                </div>

                <div class="space-y-1.5">

                    @php $activeViolet = 'bg-gradient-to-r from-violet-500/20 to-transparent text-violet-400 border-l-4 border-violet-400 shadow-[inset_0px_0px_20px_rgba(139,92,246,0.1)]'; @endphp
                    <a href="{{ route('admin.settings.index') }}"
                        class="{{ $linkBase }} {{ $isActive('admin.settings.*') ? $activeViolet : $inactiveBase }} hover:text-violet-300">
                        <svg class="{{ $iconBase }} {{ $isActive('admin.settings.*') ? 'text-violet-400' : 'text-slate-500 group-hover:text-violet-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>System Settings</span>
                    </a>

                    @php $activeEmerald = 'bg-gradient-to-r from-emerald-500/20 to-transparent text-emerald-400 border-l-4 border-emerald-400 shadow-[inset_0px_0px_20px_rgba(16,185,129,0.1)]'; @endphp
                    <a href="{{ route('admin.personnel.index') }}"
                        class="{{ $linkBase }} {{ $isActive('admin.personnel.*') ? $activeEmerald : $inactiveBase }} hover:text-emerald-300">
                        <svg class="{{ $iconBase }} {{ $isActive('admin.personnel.*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-emerald-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Personnel Setup</span>
                    </a>

                    @php $activeFuchsia = 'bg-gradient-to-r from-fuchsia-500/20 to-transparent text-fuchsia-400 border-l-4 border-fuchsia-400 shadow-[inset_0px_0px_20px_rgba(217,70,239,0.1)]'; @endphp
                    <a href="{{ route('admin.lis_sync.index') }}"
                        class="{{ $linkBase }} {{ $isActive('admin.lis_sync.*') ? $activeFuchsia : $inactiveBase }} hover:text-fuchsia-300">
                        <svg class="{{ $iconBase }} {{ $isActive('admin.lis_sync.*') ? 'text-fuchsia-400' : 'text-slate-500 group-hover:text-fuchsia-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span>LIS Data Sync</span>
                    </a>

                    @php $activeAmber = 'bg-gradient-to-r from-amber-500/20 to-transparent text-amber-400 border-l-4 border-amber-400 shadow-[inset_0px_0px_20px_rgba(245,158,11,0.1)]'; @endphp
                    <a href="{{ route('admin.inventory.index') }}"
                        class="{{ $linkBase }} {{ $isActive('admin.inventory.index') ? $activeAmber : $inactiveBase }} hover:text-amber-300">
                        <svg class="{{ $iconBase }} {{ $isActive('admin.inventory.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-amber-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <span>Master Inventory</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
