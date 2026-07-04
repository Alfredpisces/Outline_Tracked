<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-900 tracking-tight flex items-center gap-3">
                <span class="bg-violet-600 text-white p-2 rounded-lg text-lg">⚖️</span>
                {{ __('Violator Database') }}
            </h2>

            <div
                class="flex items-center gap-3 text-sm font-black text-slate-500 uppercase tracking-widest bg-white border border-slate-200 px-5 py-2.5 rounded-full shadow-sm">
                Behavioral Records
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Search --}}
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('counselor.violators.index') }}"
                    class="flex flex-col gap-3 sm:flex-row sm:items-center">

                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <input type="text" name="q" value="{{ $search ?? '' }}"
                            placeholder="Search by student name or LRN..."
                            class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 transition-all font-medium text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-5 py-3 bg-slate-900 hover:bg-violet-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-all">
                            Search
                        </button>

                        <a href="{{ route('counselor.violators.index') }}"
                            class="px-5 py-3 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm transition-all">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th
                                class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Student
                            </th>
                            <th
                                class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Total Offenses
                            </th>
                            <th
                                class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Latest Infraction
                            </th>
                            <th
                                class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Status
                            </th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($students as $student)
                            @php
                                $first = $student->first_name ?? '';
                                $last = $student->last_name ?? '';
                                $fullName = trim($first . ' ' . $last) ?: 'N/A';

                                $initial = '';
                                if (!empty($first)) {
                                    $initial = strtoupper(substr($first, 0, 1));
                                } elseif (!empty($last)) {
                                    $initial = strtoupper(substr($last, 0, 1));
                                } else {
                                    $initial = 'S';
                                }

                                $latestOffense = optional($student->incidentReports->first())->offense_type ?? '—';
                            @endphp

                            <tr class="hover:bg-violet-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center font-black text-lg">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900">
                                                {{ $fullName }}
                                            </div>
                                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                                LRN: {{ $student->lrn ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 font-black text-slate-700">
                                    {{ $student->incident_reports_count ?? 0 }}
                                </td>

                                <td class="px-6 py-5 text-sm font-medium text-slate-600">
                                    {{ $latestOffense }}
                                </td>

                                <td class="px-6 py-5">
                                    @if ($student->isEligibleForGoodMoral())
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 uppercase tracking-widest border border-emerald-200">
                                            Eligible
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 uppercase tracking-widest border border-rose-200">
                                            Blocked
                                        </span>
                                    @endif
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('counselor.violators.show', $student->id) }}"
                                            class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                            View History
                                        </a>

                                        <a href="{{ route('counselor.incidents.create', ['prefill_lrn' => $student->lrn]) }}"
                                            class="inline-flex items-center px-4 py-2 bg-violet-50 hover:bg-violet-600 text-violet-700 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-violet-200 hover:border-violet-600 shadow-sm">
                                            Add New Offense
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center font-bold text-slate-400">
                                    No violator records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-6 border-t border-slate-100">
                    {{ $students->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
