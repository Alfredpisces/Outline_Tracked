<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl md:text-3xl text-slate-900 tracking-tight">
                {{ __('Review Disciplinary Incidents') }}
            </h2>
            <a href="{{ route('counselor.incidents.create') }}"
                class="inline-flex items-center px-6 py-3 bg-slate-900 hover:bg-rose-600 text-white rounded-xl text-sm font-black shadow-lg transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Log New Incident
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="incident-search" onkeyup="filterIncidents()"
                    placeholder="Search by student name, LRN, or offense..."
                    class="block w-full pl-12 pr-6 py-4 text-base bg-white border-slate-200 rounded-2xl shadow-sm focus:border-rose-500 focus:ring-rose-500 font-medium placeholder-slate-400">
            </div>

            <div id="incident-list" class="space-y-6">
                @forelse($incidentReports as $incident)
                    <div
                        class="incident-card bg-white shadow-sm rounded-3xl p-8 border border-slate-100 transition-all hover:shadow-md">

                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-3">
                                <h3 class="font-black text-xl text-slate-900">
                                    {{ $incident->student->lrn ?? 'Unknown LRN' }} —
                                    {{ $incident->student->first_name ?? 'Student' }}
                                    {{ $incident->student->last_name ?? 'Record' }}
                                </h3>

                                @if (strtolower($incident->severity) === 'critical')
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 uppercase tracking-widest">Critical
                                        / Legal</span>
                                @elseif (strtolower($incident->severity) === 'major')
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 uppercase tracking-widest">Major
                                        Offense</span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 uppercase tracking-widest">Minor
                                        Offense</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('counselor.incidents.edit', $incident->id) }}"
                                    class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit Offense">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>

                                <form action="{{ route('counselor.incidents.destroy', $incident->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to permanently delete this offense record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Delete Offense">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between md:items-start gap-6 mb-8">
                            <div class="flex-grow">
                                <p class="text-sm font-medium text-slate-500">
                                    <span
                                        class="text-slate-900 font-bold offense-type">{{ $incident->offense_type }}</span>
                                    reported by {{ $incident->teacher->name ?? 'System' }}
                                </p>

                                <div
                                    class="mt-4 bg-slate-50 p-5 rounded-2xl border border-slate-100 text-sm text-slate-700 leading-relaxed">
                                    <span
                                        class="font-black text-slate-900 uppercase text-[10px] tracking-widest block mb-2">Narrative:</span>
                                    "{{ $incident->narrative }}"
                                </div>

                                @if ($incident->evidence_path)
                                    <div class="mt-4">
                                        <span
                                            class="font-black text-slate-400 uppercase text-[10px] tracking-widest block mb-2">Evidence:</span>
                                        <a href="{{ asset('storage/' . $incident->evidence_path) }}" target="_blank"
                                            class="group relative block w-20 h-20 rounded-xl overflow-hidden border border-slate-200">
                                            <img src="{{ asset('storage/' . $incident->evidence_path) }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[10px] font-bold transition-opacity">
                                                TAP TO VIEW</div>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="text-sm font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">
                                Filed: {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-8">
                            <form action="{{ route('counselor.incidents.resolve', $incident) }}" method="POST"
                                class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                                @csrf
                                @method('PUT')

                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Case
                                        Status</label>
                                    <select name="status"
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm font-bold">
                                        <option value="in_intervention" @selected($incident->status == 'in_intervention')>Active /
                                            In-Progress
                                        </option>
                                        <option value="resolved" @selected($incident->status == 'resolved')>Resolved / Closed</option>
                                        <option value="escalated" @selected($incident->status == 'escalated')>Escalated Internally
                                        </option>
                                        <option value="turned_over" @selected($incident->status == 'turned_over')>Turned Over
                                            (External/Legal)</option>
                                    </select>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Strategy</label>
                                    <input type="text" name="intervention_type"
                                        value="{{ $incident->intervention_type }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm font-bold">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Follow-up</label>
                                    <input type="datetime-local" name="scheduled_at"
                                        value="{{ $incident->scheduled_at ? \Carbon\Carbon::parse($incident->scheduled_at)->format('Y-m-d\TH:i') : '' }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm font-bold">
                                </div>

                                <div class="flex flex-col gap-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Principal
                                        Action</label>
                                    <input type="text" name="principal_action"
                                        value="{{ $incident->principal_action }}"
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm font-bold">
                                </div>

                                <div class="flex flex-col gap-2 md:col-span-3">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resolution
                                        Notes</label>
                                    <textarea name="notes" rows="2" required
                                        class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-sm font-medium">{{ $incident->notes ?? $incident->counselor_notes }}</textarea>
                                </div>

                                <button type="submit"
                                    class="w-full px-4 py-3.5 bg-slate-900 hover:bg-rose-600 text-white rounded-xl text-sm font-black shadow-lg transition-all">
                                    Update Case File
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-3xl p-16 text-center border border-slate-100">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">All Clear</h3>
                        <p class="text-slate-500 mt-2">There are currently no active disciplinary incidents requiring
                            review.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function filterIncidents() {
            const input = document.getElementById('incident-search').value.toLowerCase();
            const cards = document.getElementsByClassName('incident-card');

            for (let i = 0; i < cards.length; i++) {
                const text = cards[i].innerText.toLowerCase();
                cards[i].style.display = text.includes(input) ? "" : "none";
            }
        }
    </script>
</x-app-layout>
