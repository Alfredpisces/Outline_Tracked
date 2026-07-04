<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    {{ __('Edit SF1 Staging Record') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Update fields carefully. Duplicate (LRN + School Year) is not allowed.
                </p>
            </div>

            <a href="{{ route('admin.lis_sync.manage', ['school_year' => $lisStaging->school_year]) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm transition hover:bg-slate-50">
                Back to Manage
            </a>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 shadow-sm">
                    <div class="font-black mb-2 uppercase tracking-widest text-[10px]">Please fix the following:</div>
                    <ul class="list-disc pl-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.lis_sync.update', $lisStaging) }}"
                class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">LRN (Learner
                            Reference Number)</label>
                        <input name="lrn" value="{{ old('lrn', $lisStaging->lrn) }}" required
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-black text-indigo-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">First
                            Name</label>
                        <input name="first_name" value="{{ old('first_name', $lisStaging->first_name) }}" required
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Last
                            Name</label>
                        <input name="last_name" value="{{ old('last_name', $lisStaging->last_name) }}" required
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Middle
                            Name</label>
                        <input name="middle_name" value="{{ old('middle_name', $lisStaging->middle_name) }}"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Extension
                            Name</label>
                        <input name="extension_name" value="{{ old('extension_name', $lisStaging->extension_name) }}"
                            placeholder="e.g. JR, SR, III"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Gender</label>
                        <select name="gender"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Male" {{ old('gender', $lisStaging->gender) == 'Male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="Female"
                                {{ old('gender', $lisStaging->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-500">Birthdate</label>
                        <input name="birthdate" value="{{ old('birthdate', $lisStaging->birthdate) }}"
                            placeholder="e.g. 08-08-2010"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Grade
                            Level</label>
                        <input name="grade_level" value="{{ old('grade_level', $lisStaging->grade_level) }}" required
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Section</label>
                        <input name="section" value="{{ old('section', $lisStaging->section) }}" required
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-black uppercase tracking-widest text-slate-500">Curriculum</label>
                        <input name="curriculum" value="{{ old('curriculum', $lisStaging->curriculum) }}"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Status</label>
                        <input name="status" value="{{ old('status', $lisStaging->status) }}"
                            class="mt-2 w-full rounded-2xl border-slate-300 px-4 py-3 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end border-t border-slate-100 pt-6">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-8 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm hover:bg-indigo-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.lis_sync.destroy', $lisStaging) }}"
                onsubmit="return confirm('Delete this record permanently?');" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="text-[10px] font-black uppercase tracking-widest text-rose-500 hover:text-rose-700 transition underline">
                    Permanently Delete Record
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
