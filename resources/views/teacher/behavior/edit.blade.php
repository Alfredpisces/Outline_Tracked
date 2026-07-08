<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.behavior.index') }}"
                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-black text-2xl text-slate-800 leading-tight">
                {{ __('Edit Pending Incident') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 border-t-4 border-t-indigo-500">

            <div class="mb-6 border-b border-slate-100 pb-5 flex justify-between items-end">
                <div>
                    <h3 class="text-lg font-black text-slate-800">Revise Intake Form</h3>
                    <p class="text-sm text-slate-500 mt-1">Make corrections before the Counselor reviews the case.</p>
                </div>
                <span
                    class="bg-amber-100 text-amber-800 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-lg">Pending
                    Review</span>
            </div>

            <form action="{{ route('teacher.behavior.update', $incident->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Locked Target Student --}}
                    <div class="col-span-1 md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 ml-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Locked Target Student
                        </label>
                        <div class="text-lg font-black text-slate-800 ml-1">
                            {{ $incident->student->last_name ?? '' }}, {{ $incident->student->first_name ?? '' }}
                            <span class="text-sm font-semibold text-slate-500">(Grade
                                {{ $incident->student->grade_level ?? '' }} -
                                {{ $incident->student->section ?? '' }})</span>
                        </div>
                    </div>

                    {{-- Offense Category --}}
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Offense
                            Classification</label>
                        <select name="offense_type" required
                            class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all">
                            <option value="Minor Disruption"
                                {{ $incident->offense_type == 'Minor Disruption' ? 'selected' : '' }}>Minor Class
                                Disruption</option>
                            <option value="Dress Code Violation"
                                {{ $incident->offense_type == 'Dress Code Violation' ? 'selected' : '' }}>Dress Code /
                                ID Violation</option>
                            <option value="Vandalism" {{ $incident->offense_type == 'Vandalism' ? 'selected' : '' }}>
                                Vandalism / Property Damage</option>
                            <option value="Physical Altercation"
                                {{ $incident->offense_type == 'Physical Altercation' ? 'selected' : '' }}>Physical
                                Altercation / Fighting</option>
                            <option value="Bullying/Harassment"
                                {{ $incident->offense_type == 'Bullying/Harassment' ? 'selected' : '' }}>Bullying /
                                Harassment</option>
                            <option value="Academic Dishonesty"
                                {{ $incident->offense_type == 'Academic Dishonesty' ? 'selected' : '' }}>Academic
                                Dishonesty</option>
                            <option value="Insubordination"
                                {{ $incident->offense_type == 'Insubordination' ? 'selected' : '' }}>Insubordination
                            </option>
                            <option value="Unauthorized Exit"
                                {{ $incident->offense_type == 'Unauthorized Exit' ? 'selected' : '' }}>Unauthorized
                                Exit</option>
                        </select>
                    </div>

                    {{-- Severity --}}
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Severity
                            Rating</label>
                        <select name="severity" required
                            class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all">
                            <option value="minor" {{ $incident->severity == 'minor' ? 'selected' : '' }}>Minor Offense
                            </option>
                            <option value="major" {{ $incident->severity == 'major' ? 'selected' : '' }}>Major Offense
                            </option>
                            <option value="critical" {{ $incident->severity == 'critical' ? 'selected' : '' }}>Critical
                                / Legal Matter</option>
                        </select>
                    </div>

                    {{-- Incident Date --}}
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Date
                            of Occurrence</label>
                        <input type="date" name="incident_date" required
                            value="{{ \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d') }}"
                            class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-indigo-500 shadow-sm">
                    </div>

                    {{-- Narrative --}}
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Factual
                            Narrative</label>
                        <textarea name="narrative" required rows="4"
                            class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all resize-none">{{ $incident->narrative }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ route('teacher.behavior.index') }}"
                        class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition">Cancel</a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest px-8 py-3 rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                        Save Revisions
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
