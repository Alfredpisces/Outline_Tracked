<x-guest-layout>
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-lg font-bold text-white shadow-lg uppercase">
                {{ substr(Auth::user()->first_name, 0, 2) }}
            </div>
        </div>
        <h2 class="text-xl font-black text-slate-900">Select Workspace</h2>
        <p class="text-sm text-slate-500 mt-2">Welcome, {{ Auth::user()->first_name }}. Which role will you assume today?
        </p>
    </div>

    <div class="space-y-3">
        <!-- Dynamically loop through the user's assigned roles -->
        @foreach (Auth::user()->roles as $role)
            <form method="POST" action="{{ route('role.switch', $role->name) }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-between px-5 py-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-500 hover:ring-1 hover:ring-indigo-500 transition-all group shadow-sm">
                    <span class="font-bold text-slate-700 capitalize group-hover:text-indigo-600">
                        {{ str_replace('_', ' ', $role->name) }} Workspace
                    </span>
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-400 hover:text-slate-600 font-medium underline">
                Sign out instead
            </button>
        </form>
    </div>
</x-guest-layout>
