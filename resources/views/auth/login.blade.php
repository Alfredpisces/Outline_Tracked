<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="flex justify-center mb-4">
            <!-- TRACKED SVG LOGO -->
            <div
                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-[0_0_20px_rgba(99,102,241,0.5)] p-3">
                <svg class="w-full h-full text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                    <path d="M2 17l10 5 10-5"></path>
                    <path d="M2 12l10 5 10-5"></path>
                </svg>
            </div>
        </div>

        <!-- DYNAMIC SCHOOL TITLE -->
        <h1 class="text-2xl font-black text-white">{{ \App\Models\Setting::get('school_name', 'TrackEd System') }}</h1>
        <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-[0.2em] mt-1">Access Management</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="login" value="Email or Employee ID"
                class="text-slate-300 text-xs font-bold uppercase" />
            <div class="relative mt-1">
                <x-text-input id="login"
                    class="w-full bg-slate-800/50 border-white/10 text-white placeholder-slate-500 rounded-xl focus:border-indigo-500"
                    type="text" name="login" :value="old('login')" required autofocus
                    placeholder="name@deped.edu.ph" />
            </div>
            <x-input-error :messages="$errors->get('login')" class="mt-1 text-red-400" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="text-slate-300 text-xs font-bold uppercase" />
            <div class="relative mt-1">
                <x-text-input id="password"
                    class="w-full bg-slate-800/50 border-white/10 text-white placeholder-slate-500 rounded-xl focus:border-indigo-500"
                    type="password" name="password" required placeholder="••••••••" />
            </div>
        </div>

        <button
            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-xl font-black uppercase tracking-widest transition-all shadow-[0_0_15px_rgba(79,70,229,0.4)]">
            {{ __('Log in') }}
        </button>

        <p class="text-center text-[10px] text-slate-500">Need help? Contact your School ICT Coordinator.</p>
    </form>
</x-guest-layout>
