<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Setting::get('school_name', config('app.name', 'TrackEd')) }} - Workspace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }"
    @keydown.escape.window="sidebarOpen = false">

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-400/10 blur-[100px]"></div>
        <div class="absolute top-[20%] right-[-5%] w-[30%] h-[50%] rounded-full bg-emerald-400/10 blur-[120px]"></div>
    </div>

    <div class="flex h-screen w-full overflow-hidden">

        <div x-show="sidebarOpen" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"
            aria-hidden="true" x-cloak></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 flex-shrink-0 flex flex-col bg-slate-900 shadow-2xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            @if (request()->routeIs('school_head.*'))
                <x-sh-sidebar />
            @elseif (request()->routeIs('counselor.*'))
                <x-c-sidebar />
            @elseif (request()->routeIs('teacher.*'))
                <x-t-sidebar />
            @else
                @include('layouts.sidebar')
            @endif

        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">

            <header
                class="sticky top-0 z-30 bg-white/70 backdrop-blur-xl border-b border-slate-200/50 flex items-center justify-between px-4 py-3 lg:px-8 shrink-0">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="p-2 -ml-2 text-slate-500 rounded-xl lg:hidden hover:bg-blue-50 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div
                        class="hidden sm:flex items-center gap-3 border-l border-slate-200 pl-4 ml-2 lg:border-none lg:pl-0 lg:ml-0">
                        @if (\App\Models\Setting::get('has_custom_logo') === 'true')
                            <img src="{{ asset('images/school-logo.png') }}?t={{ time() }}" alt="School Logo"
                                class="h-9 w-9 object-contain drop-shadow-sm">
                        @else
                            <div
                                class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex flex-col">
                            <span
                                class="font-black text-slate-800 tracking-tight leading-none">{{ \App\Models\Setting::get('school_name', 'TrackEd Systems') }}</span>
                            @if (\App\Models\Setting::get('school_id'))
                                <span
                                    class="text-[10px] font-bold text-slate-500 tracking-widest uppercase mt-0.5">DepEd
                                    ID: {{ \App\Models\Setting::get('school_id') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex-1"></div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                        class="flex items-center gap-3 p-1.5 pr-3 rounded-full hover:bg-white shadow-sm border border-transparent hover:border-slate-200 focus:outline-none transition-all duration-200 bg-white/50">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-500 to-purple-500 text-white flex items-center justify-center font-bold text-sm shadow-md ring-2 ring-white">
                            {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'R', 0, 1) }}
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">
                                {{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}</p>

                            <p class="text-[10px] font-black tracking-widest text-emerald-500 uppercase">
                                @if (request()->routeIs('school_head.*'))
                                    EXECUTIVE WORKSPACE
                                @elseif (request()->routeIs('counselor.*'))
                                    COUNSELOR WORKSPACE
                                @elseif (request()->routeIs('teacher.*'))
                                    FACULTY WORKSPACE
                                @elseif (request()->routeIs('admin.*'))
                                    ADMIN WORKSPACE
                                @else
                                    {{ str_replace('_', ' ', Auth::user()->role ?? 'Guest') }}
                                @endif
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" x-transition.opacity
                        class="absolute right-0 w-56 mt-3 bg-white border border-slate-100 rounded-2xl shadow-2xl z-50 overflow-hidden"
                        style="display: none;">
                        <div class="p-2 space-y-1">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm font-medium text-slate-700 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition-colors">
                                Profile Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center px-4 py-2 text-left text-sm text-rose-600 rounded-xl hover:bg-rose-50 hover:text-rose-700 font-medium transition-colors">
                                    Secure Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 w-full relative">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div class="relative z-[9999]">

        <button onclick="toggleTrackEdChat()" type="button"
            class="fixed bottom-6 right-6 w-14 h-14 bg-indigo-600 rounded-full shadow-2xl text-white flex items-center justify-center hover:bg-indigo-700 transition transform hover:scale-105 z-50 border-4 border-white cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <div id="tracked-chat-window"
            class="fixed bottom-24 right-6 w-80 sm:w-96 h-[580px] transition-all duration-300 transform translate-y-8 opacity-0 pointer-events-none flex flex-col bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">

            <div class="bg-indigo-600 px-5 py-4 flex items-center justify-between shadow-md shrink-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white">🤖</div>
                    <div>
                        <h4 class="text-white font-black text-sm">System Guide</h4>
                        <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest">TrackEd Assistant</p>
                    </div>
                </div>
                <button onclick="toggleTrackEdChat()" type="button"
                    class="text-indigo-200 hover:text-white transition text-xl leading-none">✕</button>
            </div>

            <div class="flex-1 overflow-hidden relative">
                <livewire:global-copilot />
            </div>

        </div>
    </div>

    <script>
        function toggleTrackEdChat() {
            const chatWindow = document.getElementById('tracked-chat-window');
            // Toggle visibility classes
            chatWindow.classList.toggle('translate-y-8');
            chatWindow.classList.toggle('opacity-0');
            chatWindow.classList.toggle('pointer-events-none');
        }
    </script>
</body>

</html>
