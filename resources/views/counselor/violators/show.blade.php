<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('counselor.violators.index') }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500"
                    aria-label="Back">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900">Student Behavioral Profile</h2>
                    <p class="mt-1 text-sm text-slate-500">Behavioral history, offenses, and interventions.</p>
                </div>
            </div>

            <a href="{{ route('counselor.incidents.create', ['prefill_lrn' => $student->lrn]) }}"
                class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                + Add New Offense
            </a>
        </div>
    </x-slot>

    <div class="min-h-[calc(100vh-10rem)] bg-slate-50 py-8 sm:py-10">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- Profile --}}
            <section class="relative overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-violet-100/70 blur-2xl"></div>
                    <div class="absolute -left-24 -bottom-24 h-72 w-72 rounded-full bg-sky-100/70 blur-2xl"></div>
                </div>

                <div class="relative p-6 sm:p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center">

                        {{-- Avatar + name --}}
                        <div class="flex items-center gap-5">
                            <div
                                class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-violet-100 text-3xl font-black text-violet-700 shadow-inner ring-1 ring-violet-200/60">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <h1 class="truncate text-2xl sm:text-3xl font-black text-slate-900">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h1>

                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span
                                        class="rounded-lg bg-slate-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                        LRN: {{ $student->lrn }}
                                    </span>

                                    <span
                                        class="rounded-lg bg-slate-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                        Grade {{ $student->grade_level ?? 'N/A' }}
                                    </span>

                                    @if ($student->isEligibleForGoodMoral())
                                        <span
                                            class="rounded-lg bg-emerald-100 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-emerald-700 ring-1 ring-emerald-200/60">
                                            Good Moral: Eligible
                                        </span>
                                    @else
                                        <span
                                            class="rounded-lg bg-rose-100 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-rose-700 ring-1 ring-rose-200/60">
                                            Good Moral: Blocked
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Stats --}}
                        @php
                            $total = $student->incidentReports->count();
                            $open = $student->incidentReports->where('status', '!=', 'resolved')->count();
                            $latest = $student->incidentReports->sortByDesc('incident_date')->first();
                        @endphp

                        <div class="lg:ml-auto w-full lg:w-auto">
                            <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="text-2xl font-black text-violet-700">{{ $total }}</div>
                                    <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Total Offenses
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="text-2xl font-black text-slate-900">{{ $open }}</div>
                                    <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Active / Pending
                                    </div>
                                </div>

                                <div
                                    class="col-span-2 lg:col-span-1 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                    <div class="text-sm font-black text-slate-900 truncate">
                                        {{ $latest?->offense_type ?? '—' }}
                                    </div>
                                    <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        Latest Infraction
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <p class="mt-6 text-sm text-slate-500">
                        This profile summarizes recorded incident reports and interventions for guidance and
                        documentation.
                    </p>
                </div>
            </section>

            {{-- Timeline --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="flex items-center text-sm font-black uppercase tracking-[0.2em] text-slate-700">
                        <span class="mr-4 h-[2px] w-10 bg-slate-300"></span>
                        Disciplinary Timeline
                    </h3>
                    <div class="text-xs text-slate-500">
                        Showing {{ $total }} record(s)
                    </div>
                </div>

                @php
                    $incidents = $student->incidentReports->sortByDesc('incident_date');
                @endphp

                @if ($incidents->isEmpty())
                    <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                        <h4 class="text-lg font-bold text-slate-900">No incidents found</h4>
                        <p class="mt-2 text-sm text-slate-500">This student has no recorded incident reports yet.</p>
                    </div>
                @else
                    <div class="relative space-y-6 rounded-3xl border border-slate-100 bg-white p-5 sm:p-6 shadow-sm">
                        <div
                            class="absolute inset-y-0 left-7 hidden w-px bg-gradient-to-b from-transparent via-slate-200 to-transparent sm:block">
                        </div>

                        @foreach ($incidents as $incident)
                            @php
                                $severityColor = $incident->severity === 'major' ? 'rose' : 'amber';
                                $statusClass =
                                    $incident->status === 'resolved'
                                        ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200/60'
                                        : 'bg-blue-100 text-blue-700 ring-1 ring-blue-200/60';

                                $severityBadge =
                                    $severityColor === 'rose'
                                        ? 'bg-rose-100 text-rose-700 ring-1 ring-rose-200/60'
                                        : 'bg-amber-100 text-amber-700 ring-1 ring-amber-200/60';
                            @endphp

                            <article class="relative sm:pl-10">
                                {{-- Dot --}}
                                <div
                                    class="absolute left-5 top-6 hidden h-4 w-4 -translate-x-1/2 rounded-full border-4 border-white bg-slate-200 sm:block">
                                </div>

                                <div class="rounded-2xl border border-slate-100 bg-slate-50/40 p-5 sm:p-6">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            {{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="rounded-lg px-2.5 py-1 text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $incident->status) }}
                                            </span>
                                            <span
                                                class="rounded-lg px-2.5 py-1 text-[9px] font-black uppercase tracking-widest {{ $severityBadge }}">
                                                {{ $incident->severity ?? 'minor' }}
                                            </span>
                                        </div>
                                    </div>

                                    <h4 class="mt-3 text-lg font-extrabold text-slate-900">
                                        {{ $incident->offense_type }}
                                    </h4>

                                    <p class="mt-2 text-sm text-slate-600">
                                        <span class="font-semibold text-slate-700">Narrative:</span>
                                        <span class="italic text-slate-500">“{{ $incident->narrative }}”</span>
                                    </p>

                                    <div class="mt-4 border-t border-slate-200/60 pt-4 space-y-3">
                                        <div class="text-xs font-bold text-slate-700">
                                            Action:
                                            <span class="font-medium text-slate-500">
                                                {{ $incident->intervention_type ?? 'Pending' }}
                                            </span>
                                        </div>

                                        {{-- NEW: Manage buttons per incident (Option 1) --}}
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('counselor.incidents.edit', $incident) }}"
                                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-600 shadow-sm hover:bg-slate-50">
                                                Edit
                                            </a>

                                            <form action="{{ route('counselor.incidents.destroy', $incident) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this incident record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-rose-700 shadow-sm hover:bg-rose-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
