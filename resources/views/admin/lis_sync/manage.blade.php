<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-sm shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight">
                        {{ __('Manage LIS Directory') }}
                    </h2>
                    <p class="mt-0.5 text-sm text-slate-500 font-medium">
                        Search records, edit or delete per row, or perform an ultimate reset.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.lis_sync.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm transition hover:bg-slate-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Sync
                </a>

                <a href="{{ route('admin.lis_sync.reset') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-rose-700 hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Ultimate Delete
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // Sort state — read from the query string. The controller should apply
        // ->orderBy(request('sort', 'last_name'), request('direction', 'asc'))
        // (whitelisting 'grade_level' and 'school_year') before paginating $records.
        $sortField = request('sort', '');
        $sortDir = request('direction', 'asc');
        $flipDir = $sortDir === 'asc' ? 'desc' : 'asc';
    @endphp

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div
                    class="flex items-center gap-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-r-xl font-bold text-sm shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- FILTER / SEARCH / SORT BAR --}}
                <div class="border-b border-slate-100 bg-slate-50/50 p-4">
                    <form method="GET" action="{{ route('admin.lis_sync.manage') }}"
                        class="flex flex-col gap-3 lg:flex-row lg:items-center">

                        <div class="grid grid-cols-2 gap-3 lg:flex lg:items-center lg:gap-3 lg:shrink-0">
                            <div class="relative">
                                <select name="school_year"
                                    class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm cursor-pointer"
                                    onchange="this.form.submit()">
                                    <option value="">All School Years</option>
                                    @foreach ($schoolYears as $sy)
                                        <option value="{{ $sy }}" {{ $syFilter === $sy ? 'selected' : '' }}>SY
                                            {{ $sy }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <select name="sort"
                                    class="w-full rounded-xl border-slate-300 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm cursor-pointer"
                                    onchange="this.form.submit()">
                                    <option value="">Sort: Default</option>
                                    <option value="grade_level" {{ $sortField === 'grade_level' ? 'selected' : '' }}>
                                        Sort: Grade Level</option>
                                    <option value="school_year" {{ $sortField === 'school_year' ? 'selected' : '' }}>
                                        Sort: School Year</option>
                                </select>

                                @if ($sortField)
                                    <a href="{{ request()->fullUrlWithQuery(['direction' => $flipDir, 'page' => null]) }}"
                                        title="Toggle sort direction"
                                        class="shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-300 bg-white text-slate-500 shadow-sm hover:bg-slate-50 hover:text-indigo-600 transition">
                                        @if ($sortDir === 'asc')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 4h13M3 8h9M3 12h5m6-4v12m0 0l-3-3m3 3l3-3" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 4h5m-5 4h9m-9 4h13m-4-8v12m0 0l3-3m-3 3l-3-3" />
                                            </svg>
                                        @endif
                                    </a>
                                    <input type="hidden" name="direction" value="{{ $sortDir }}">
                                @endif
                            </div>
                        </div>

                        <div class="relative flex-1">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search by LRN or Name..."
                                class="w-full rounded-xl border-slate-300 pl-10 text-sm font-semibold shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($syFilter || $search || $sortField)
                                <a href="{{ route('admin.lis_sync.manage') }}"
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-black uppercase tracking-widest text-slate-500 shadow-sm hover:bg-slate-50 hover:text-slate-700 transition">
                                    Clear
                                </a>
                            @endif
                            <button type="submit"
                                class="rounded-xl bg-slate-900 px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm hover:bg-slate-800 hover:shadow-md hover:-translate-y-0.5 transition flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    LRN
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    Student Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest {{ in_array($sortField, ['grade_level', 'school_year']) ? 'text-indigo-600' : 'text-slate-500' }}">
                                    Grade & Section
                                    @if (in_array($sortField, ['grade_level', 'school_year']))
                                        <span class="ml-1 text-indigo-400">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    Demographics
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($records as $r)
                                @php
                                    $isMale = strtolower($r->gender) === 'male';
                                    $initials = strtoupper(
                                        mb_substr($r->first_name, 0, 1) . mb_substr($r->last_name, 0, 1),
                                    );
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900 tabular-nums">
                                        {{ $r->lrn }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="hidden sm:flex shrink-0 h-9 w-9 items-center justify-center rounded-full text-[11px] font-black {{ $isMale ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-indigo-900">{{ $r->last_name }},
                                                    {{ $r->first_name }} {{ $r->extension_name }}</div>
                                                <div class="text-xs font-bold text-slate-500">{{ $r->middle_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-bold text-slate-800">{{ $r->grade_level }}</div>
                                        <div class="text-xs text-slate-500">{{ $r->section }} <span
                                                class="text-slate-400">({{ $r->school_year }})</span></div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $isMale ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $r->gender }}
                                        </span>
                                        <div class="text-xs text-slate-500 mt-1">{{ $r->birthdate }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.lis_sync.edit', $r) }}"
                                                class="inline-flex items-center gap-1.5 rounded-xl bg-white border border-slate-200 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-600 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.lis_sync.destroy', $r) }}"
                                                class="m-0" onsubmit="return confirm('Delete this record?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-rose-700 shadow-sm hover:bg-rose-100 hover:border-rose-300 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-400">No imported SF1 staging records
                                                found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 p-4 bg-slate-50/50">
                    {{ $records->links() }}
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
