<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg mr-3 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <h2 class="font-extrabold text-2xl md:text-3xl text-slate-800 tracking-tight">
                    {{ __('Edit Disciplinary Incident') }}
                </h2>
            </div>
            <a href="{{ route('counselor.incidents.index') }}"
                class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors flex items-center bg-white px-4 py-2.5 rounded-lg shadow-sm border border-slate-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cancel Editing
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div
                class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between w-full shadow-sm">
                <div class="flex items-center w-full">
                    <div
                        class="w-12 h-12 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center flex-shrink-0 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <div class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Target Locked
                            (Cannot be changed)</div>
                        <div class="text-xl font-black text-slate-900">{{ $incident->student->first_name }}
                            {{ $incident->student->last_name }}</div>
                        <div class="text-sm font-medium text-slate-600">LRN: {{ $incident->student->lrn }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-10">
                <form action="{{ route('counselor.incidents.update', $incident->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- INCIDENT DETAILS -->
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-5">
                            <h4
                                class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center mb-4">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                The Incident
                            </h4>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Offense
                                    Category</label>
                                <input type="text" name="offense_type"
                                    value="{{ old('offense_type', $incident->offense_type) }}" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Severity
                                    Classification</label>
                                <select name="severity" id="severity-select" onchange="handleSeverityChange()" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm transition-colors">
                                    <option value="minor"
                                        {{ old('severity', $incident->severity) == 'minor' ? 'selected' : '' }}>Minor
                                        Offense (Monitored internally)</option>
                                    <option value="major"
                                        {{ old('severity', $incident->severity) == 'major' ? 'selected' : '' }}>Major
                                        Offense (Flags Good Moral constraints)</option>
                                    <option value="critical"
                                        {{ old('severity', $incident->severity) == 'critical' ? 'selected' : '' }}>
                                        Critical / Legal Matter (Turned over to authorities)</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Date
                                    of Occurrence</label>
                                <input type="date" name="incident_date"
                                    value="{{ old('incident_date', \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d')) }}"
                                    required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Factual
                                    Narrative</label>
                                <textarea name="narrative" rows="4" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm resize-none">{{ old('narrative', $incident->narrative) }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Update
                                    Evidence (Upload new photo to replace)</label>
                                @if ($incident->evidence_path)
                                    <div class="mb-3 text-sm text-blue-600 font-bold flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Existing photo attached
                                    </div>
                                @endif
                                <input type="file" name="evidence_photo" accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            </div>
                        </div>

                        <!-- INTERVENTION STRATEGY -->
                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-5">
                            <h4
                                class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center mb-4">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                Intervention Strategy
                            </h4>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Initial
                                    Action Taken</label>
                                <select name="intervention_type" id="intervention-select" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition-colors">
                                    <option value="" disabled
                                        {{ !$incident->intervention_type ? 'selected' : '' }}>Select primary
                                        response...</option>
                                    <option value="on_the_spot"
                                        {{ old('intervention_type', $incident->intervention_type) == 'on_the_spot' ? 'selected' : '' }}>
                                        On-the-Spot Correction (Teacher/LSG)</option>
                                    <option value="verbal_warning"
                                        {{ old('intervention_type', $incident->intervention_type) == 'verbal_warning' ? 'selected' : '' }}>
                                        Verbal Warning / Reprimand</option>
                                    <option value="counseling"
                                        {{ old('intervention_type', $incident->intervention_type) == 'counseling' ? 'selected' : '' }}>
                                        Guidance Counseling Session</option>
                                    <option value="parent_conference"
                                        {{ old('intervention_type', $incident->intervention_type) == 'parent_conference' ? 'selected' : '' }}>
                                        Parent/Guardian Conference</option>
                                    <option value="community_service"
                                        {{ old('intervention_type', $incident->intervention_type) == 'community_service' ? 'selected' : '' }}>
                                        Restorative / Community Service</option>
                                    <option value="suspension"
                                        {{ old('intervention_type', $incident->intervention_type) == 'suspension' ? 'selected' : '' }}>
                                        Suspension (Requires Administration)</option>
                                    <option value="external_escalation"
                                        {{ old('intervention_type', $incident->intervention_type) == 'external_escalation' ? 'selected' : '' }}>
                                        Escalated to External Authorities (DSWD/PNP)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Case
                                        Status</label>
                                    <select name="status" id="status-select" required
                                        class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition-colors">
                                        <option value="in_intervention"
                                            {{ old('status', $incident->status) == 'in_intervention' ? 'selected' : '' }}>
                                            Active / In-Progress</option>
                                        <option value="resolved"
                                            {{ old('status', $incident->status) == 'resolved' ? 'selected' : '' }}>
                                            Resolved / Closed</option>
                                        <option value="escalated"
                                            {{ old('status', $incident->status) == 'escalated' ? 'selected' : '' }}>
                                            Escalated Internally</option>
                                        <option value="turned_over"
                                            {{ old('status', $incident->status) == 'turned_over' ? 'selected' : '' }}>
                                            Turned Over (External/Legal)</option>
                                    </select>
                                </div>
                                <div id="followup-container" class="transition-opacity duration-300">
                                    <label
                                        class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Follow-up
                                        Schedule</label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled-at-input"
                                        value="{{ old('scheduled_at', $incident->scheduled_at ? \Carbon\Carbon::parse($incident->scheduled_at)->format('Y-m-d\TH:i') : '') }}"
                                        class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Counselor
                                    Notes / Agreements</label>
                                <textarea name="notes" id="notes-textarea" rows="4" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm resize-none transition-all">{{ old('notes', $incident->notes ?? $incident->counselor_notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-8 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-md transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // --- DYNAMIC FORM LOGIC ---
        function handleSeverityChange() {
            const severity = document.getElementById('severity-select').value;
            const intervention = document.getElementById('intervention-select');
            const status = document.getElementById('status-select');
            const followupContainer = document.getElementById('followup-container');
            const notes = document.getElementById('notes-textarea');
            const scheduledAtInput = document.getElementById('scheduled-at-input');

            if (severity === 'critical') {
                // Auto-fill and lock critical selections
                intervention.value = 'external_escalation';
                status.value = 'turned_over';

                // Hide follow-up calendar as it's out of counselor hands
                followupContainer.style.opacity = '0';
                setTimeout(() => {
                    followupContainer.style.display = 'none';
                }, 300);
                scheduledAtInput.value = ''; // Clear any existing date

                // Prompt for legal documentation details if empty
                if (!notes.value) {
                    notes.placeholder =
                        'Enter the external turnover details (e.g., Police blotter number, DSWD receiving officer, Barangay blotter)...';
                }
            } else {
                // Restore form for normal internal tracking
                followupContainer.style.display = 'block';
                setTimeout(() => {
                    followupContainer.style.opacity = '1';
                }, 10);

                // Reset text placeholder
                notes.placeholder = "Document the student's response, agreements made, and specific next steps...";
            }
        }

        // Run on load to ensure the form is correctly displayed if editing an existing critical incident
        document.addEventListener('DOMContentLoaded', () => {
            handleSeverityChange();
        });
    </script>
</x-app-layout>
