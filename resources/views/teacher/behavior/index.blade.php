<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 leading-tight">
            {{ __('Incident Reports Ledger') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-r-xl font-bold text-sm shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Action Bar --}}
        <div class="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Escalation History</h3>
                <p class="text-xs text-slate-500 mt-0.5">Track the status of incidents you have submitted to the
                    Counselor.</p>
            </div>
            <a href="{{ route('teacher.behavior.create') }}"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Submit New Report
            </a>
        </div>

        {{-- The Data Table --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Date Reported</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Target Student</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Offense Classification</th>
                            <th
                                class="px-6 py-3 text-center text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Severity</th>
                            <th
                                class="px-6 py-3 text-center text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Counselor Status</th>
                            <th
                                class="px-6 py-3 text-right text-[10px] font-black uppercase tracking-widest text-slate-500">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">

                        {{-- Dynamic Loop --}}
                        @forelse($submittedReports as $report)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-600">
                                    {{ \Carbon\Carbon::parse($report->incident_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $report->student->last_name ?? '' }},
                                    {{ $report->student->first_name ?? '' }}<br>
                                    <span class="text-[10px] font-medium text-slate-400">
                                        Grade {{ $report->student->grade_level ?? '' }} -
                                        {{ $report->student->section ?? '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-700 font-medium">
                                    {{ $report->offense_type }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest
                                        {{ $report->severity === 'minor' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $report->severity === 'major' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $report->severity === 'critical' ? 'bg-rose-100 text-rose-800 ring-1 ring-rose-500' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $report->severity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($report->status === 'pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-lg border border-amber-200">
                                            Pending Review
                                        </span>
                                    @elseif($report->status === 'in_intervention')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-lg border border-blue-200">
                                            In Intervention
                                        </span>
                                    @elseif($report->status === 'resolved')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-lg border border-emerald-200">
                                            Resolved
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-[10px] uppercase tracking-widest font-black px-3 py-1.5 rounded-lg border border-slate-200">
                                            {{ str_replace('_', ' ', $report->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($report->status === 'pending')
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit Button (FIXED ROUTE NAME) --}}
                                            <a href="{{ route('teacher.behavior.edit', $report->id) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-slate-600 shadow-sm hover:bg-slate-50 hover:text-indigo-600 transition">
                                                Edit
                                            </a>

                                            {{-- Delete Button (FIXED ROUTE NAME) --}}
                                            <form action="{{ route('teacher.behavior.destroy', $report->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to retract this report? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-rose-600 shadow-sm hover:bg-rose-100 transition">
                                                    Retract
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- Visual indicator that the record is locked --}}
                                        <span
                                            class="inline-flex items-center justify-end gap-1 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Locked
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-400">No incident reports submitted yet.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
