<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    {{ __('Reset SF1 Records') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Delete imported SF1 staging records by School Year. Use this only when you need to re-sync a whole
                    year.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.lis_sync.manage') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm transition hover:bg-slate-50">
                    Back to Manage
                </a>

                <a href="{{ route('admin.lis_sync.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-800">
                    Back to Sync
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl font-bold text-sm shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900 shadow-sm">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-3xl border border-rose-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 mb-4">
                        <svg class="h-8 w-8 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Danger Zone</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        This action will <strong>permanently delete</strong> all SF1 Staging records for the selected
                        School Year. This action cannot be undone.
                    </p>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-8">
                    <form method="POST" action="{{ route('admin.lis_sync.reset.year') }}" class="space-y-6"
                        onsubmit="return confirm('Are you absolutely sure you want to delete these records?');">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Select
                                School Year to Delete</label>
                            <select name="school_year" required
                                class="mt-2 w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 shadow-sm focus:border-rose-500 focus:ring-rose-500 cursor-pointer">
                                <option value="" disabled selected>-- Select SY --</option>
                                @foreach ($schoolYears as $sy)
                                    <option value="{{ $sy }}">{{ $sy }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-[10px] uppercase font-bold text-slate-400">Only school years present in
                                your database are shown.</p>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 text-rose-600">Type
                                the school year to confirm</label>
                            <input name="confirm_school_year" value="{{ old('confirm_school_year') }}"
                                class="mt-2 w-full rounded-xl border-rose-300 bg-white px-4 py-3 text-sm font-black text-rose-900 shadow-sm focus:border-rose-500 focus:ring-rose-500"
                                placeholder="e.g. 2025-2026" required>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-rose-600 px-6 py-3.5 text-xs font-black uppercase tracking-widest text-white shadow-sm hover:bg-rose-700 transition">
                            Permanently Delete Selected Year
                        </button>
                    </form>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
