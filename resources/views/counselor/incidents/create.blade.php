<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-2 bg-rose-500 rounded-lg mr-3 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h2 class="font-extrabold text-2xl md:text-3xl text-slate-800 tracking-tight">
                    {{ __('Log Disciplinary Incident') }}
                </h2>
            </div>
            <a href="{{ route('counselor.incidents.index') }}"
                class="text-sm font-bold text-slate-600 hover:text-rose-600 transition-colors flex items-center bg-white px-4 py-2.5 rounded-lg shadow-sm border border-slate-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Review Panel
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" id="incident-form-container">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-10 relative z-20">

                <div class="flex items-center mb-8 pb-6 border-b border-slate-100">
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center font-black text-xl mr-5 shadow-sm">
                        1
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Identify Involved Student(s)</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Search the LIS staging database. You can
                            select multiple students for a group incident.</p>
                    </div>
                </div>

                <div class="relative w-full lg:w-3/4 mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <input type="text" id="lis-search-input"
                            placeholder="Type a student's full name or 12-digit LRN..."
                            class="block w-full pl-12 pr-6 py-4 text-lg text-slate-800 bg-slate-50 border border-slate-300 rounded-xl placeholder-slate-400 font-medium transition-colors focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm"
                            autocomplete="off" onkeyup="debounceSearch()">
                    </div>

                    <div id="search-results-dropdown"
                        class="hidden absolute top-full left-0 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-50">
                        <div
                            class="px-5 py-3 bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            Database Matches
                        </div>
                        <div id="search-results-list" class="max-h-80 overflow-y-auto">
                        </div>
                    </div>
                </div>

                {{-- DYNAMIC LIST OF SELECTED STUDENTS --}}
                <div id="selected-students-container" class="mt-6 w-full lg:w-3/4 mx-auto space-y-3 empty:hidden">
                </div>
            </div>

            <div id="violation-form-section"
                class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-10 opacity-0 transition-opacity duration-500">
                <form action="{{ route('counselor.incidents.store') }}" method="POST" enctype="multipart/form-data"
                    id="main-incident-form">
                    @csrf

                    {{-- Hidden inputs for selected students will be injected here by JS --}}
                    <div id="hidden-lrn-inputs"></div>

                    <div class="flex items-center mb-8 pb-6 border-b border-slate-100">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-slate-800 text-white rounded-full flex items-center justify-center font-black text-xl mr-5 shadow-sm">
                            2
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Record Analytics Data</h3>
                            <p class="text-sm font-medium text-slate-500 mt-1">Provide the factual basis of the incident
                                and actions applied.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 space-y-5">
                            <h4
                                class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center mb-4">
                                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                                The Incident
                            </h4>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Offense
                                    Category</label>
                                {{-- THE SMART COMBOBOX --}}
                                <input list="offenses-list" name="offense_type"
                                    placeholder="Select or type an offense..." required autocomplete="off"
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm">
                                <datalist id="offenses-list">
                                    <option value="Physical Altercation / Fighting">
                                    <option value="Bullying / Harassment">
                                    <option value="Vandalism / Property Damage">
                                    <option value="Insubordination / Disrespect">
                                    <option value="Cheating / Academic Dishonesty">
                                    <option value="Theft">
                                    <option value="Possession of Contraband">
                                </datalist>
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Severity
                                    Classification</label>
                                <select name="severity" id="severity-select" onchange="handleSeverityChange()" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm">
                                    <option value="" disabled selected>Select impact level...</option>
                                    <option value="minor">Minor Offense (Monitored internally)</option>
                                    <option value="major">Major Offense (Flags Good Moral constraints)</option>
                                    <option value="critical">Critical / Legal Matter (Turned over to authorities)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Date
                                    of Occurrence</label>
                                <input type="date" name="incident_date" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm">
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Factual
                                    Narrative</label>
                                <textarea name="narrative" rows="4" placeholder="Provide a highly factual, objective account of the behavior..."
                                    required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm resize-none"></textarea>
                            </div>

                            <div class="mt-4">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Incident
                                    Evidence (Optional)</label>
                                <input type="file" name="evidence_photo" accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                            </div>
                        </div>

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
                                    onchange="handleInterventionChange()"
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition-colors">
                                    <option value="" disabled selected>Select primary response...</option>
                                    <option value="on_the_spot">On-the-Spot Correction (Teacher/LSG)</option>
                                    <option value="verbal_warning">Verbal Warning / Reprimand</option>
                                    <option value="counseling">Guidance Counseling Session</option>
                                    <option value="parent_conference">Parent/Guardian Conference</option>
                                    <option value="community_service">Restorative / Community Service</option>
                                    <option value="suspension">Suspension (Requires Administration)</option>
                                    <option value="external_escalation">Escalated to External Authorities (DSWD/PNP)
                                    </option>
                                    <option value="other" class="font-bold text-indigo-600">Other (Specify Custom
                                        Action...)</option>
                                </select>
                            </div>

                            {{-- THE ESCAPE HATCH: Custom Intervention (Hidden by default) --}}
                            <div id="intervention-custom-container" class="hidden">
                                <input type="text" name="intervention_custom" id="intervention-custom-input"
                                    placeholder="Specify custom intervention..."
                                    class="block w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg text-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm placeholder-indigo-300">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Case
                                        Status</label>
                                    <select name="status" id="status-select" required
                                        class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition-colors">
                                        <option value="in_intervention">Active / In-Progress</option>
                                        <option value="resolved">Resolved / Closed</option>
                                        <option value="escalated">Escalated Internally</option>
                                        <option value="turned_over">Turned Over (External/Legal)</option>
                                    </select>
                                </div>
                                <div id="followup-container" class="transition-opacity duration-300">
                                    <label
                                        class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Follow-up
                                        Schedule</label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled-at-input"
                                        class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Counselor
                                    Notes / Agreements</label>
                                <textarea name="notes" id="notes-textarea" rows="4"
                                    placeholder="Document the student's response, agreements made, and specific next steps..." required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm resize-none transition-all"></textarea>
                            </div>
                        </div>

                    </div>

                    <div
                        class="pt-6 mt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end">
                        <button type="submit" id="submit-btn" disabled
                            class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-slate-900 hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-sm font-bold shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            Commit Analytics Record
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // --- MULTI-SELECT STATE ---
        let selectedStudents = [];

        function handleInterventionChange() {
            const intervention = document.getElementById('intervention-select').value;
            const customContainer = document.getElementById('intervention-custom-container');
            const customInput = document.getElementById('intervention-custom-input');

            if (intervention === 'other') {
                customContainer.classList.remove('hidden');
                customInput.setAttribute('required', 'required');
            } else {
                customContainer.classList.add('hidden');
                customInput.removeAttribute('required');
                customInput.value = ''; // clear it out
            }
        }

        function handleSeverityChange() {
            const severity = document.getElementById('severity-select').value;
            const intervention = document.getElementById('intervention-select');
            const status = document.getElementById('status-select');
            const followupContainer = document.getElementById('followup-container');
            const notes = document.getElementById('notes-textarea');
            const scheduledAtInput = document.getElementById('scheduled-at-input');

            if (severity === 'critical') {
                intervention.value = 'external_escalation';
                status.value = 'turned_over';
                handleInterventionChange(); // Hide custom input if it was open

                followupContainer.style.opacity = '0';
                setTimeout(() => {
                    followupContainer.style.display = 'none';
                }, 300);
                scheduledAtInput.value = '';

                notes.placeholder =
                    'Enter the external turnover details (e.g., Police blotter number, DSWD receiving officer)...';
            } else {
                followupContainer.style.display = 'block';
                setTimeout(() => {
                    followupContainer.style.opacity = '1';
                }, 10);
                notes.placeholder = "Document the student's response, agreements made, and specific next steps...";

                if (status.value === 'turned_over') status.value = 'in_intervention';
                if (intervention.value === 'external_escalation') intervention.value = '';
            }
        }

        // --- SEARCH LOGIC ---
        let searchTimeout;

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchLisDatabase();
            }, 300);
        }

        async function searchLisDatabase() {
            const input = document.getElementById('lis-search-input').value.trim();
            const dropdown = document.getElementById('search-results-dropdown');
            const resultsList = document.getElementById('search-results-list');

            if (input.length >= 2) {
                dropdown.classList.remove('hidden');
                resultsList.innerHTML =
                    '<div class="px-6 py-8 text-sm font-bold text-rose-500 text-center">Querying Database...</div>';

                try {
                    const searchUrl = "{{ route('counselor.incidents.search_lis') }}?q=" + encodeURIComponent(input);
                    const response = await fetch(searchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error("Server Error");
                    const data = await response.json();

                    if (data.length > 0) {
                        resultsList.innerHTML = data.map(student => {
                                const fullName = `${student.last_name || ''}, ${student.first_name || ''}`.trim();
                                // Don't show students already selected
                                if (selectedStudents.some(s => s.lrn === student.lrn)) return '';

                                return `
                                <button type="button" onclick="addStudent('${student.lrn}', '${fullName.replace(/'/g, "\\'")}')" class="w-full text-left px-5 py-4 hover:bg-slate-50 flex items-center transition-colors border-b border-slate-100 last:border-0 group focus:outline-none">
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-rose-600">${fullName || 'No Name'}</div>
                                        <div class="text-xs font-medium text-slate-500 mt-0.5">LRN: ${student.lrn}</div>
                                    </div>
                                </button>
                            `;
                            }).join('') ||
                            '<div class="px-6 py-8 text-sm font-medium text-slate-500 text-center">Student already selected.</div>';
                    } else {
                        resultsList.innerHTML =
                            '<div class="px-6 py-8 text-sm font-medium text-slate-500 text-center">No records matched.</div>';
                    }
                } catch (error) {
                    resultsList.innerHTML =
                        `<div class="px-6 py-6 text-sm text-red-500 text-center">Search failed.</div>`;
                }
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Add a student to the array and UI
        function addStudent(lrn, name) {
            document.getElementById('search-results-dropdown').classList.add('hidden');
            document.getElementById('lis-search-input').value = '';
            document.getElementById('lis-search-input').focus();

            if (!selectedStudents.some(s => s.lrn === lrn)) {
                selectedStudents.push({
                    lrn,
                    name
                });
                renderSelectedStudents();
            }
        }

        // Remove a student from the array and UI
        function removeStudent(lrn) {
            selectedStudents = selectedStudents.filter(s => s.lrn !== lrn);
            renderSelectedStudents();
        }

        // Render the array to the screen and generate hidden inputs
        function renderSelectedStudents() {
            const container = document.getElementById('selected-students-container');
            const hiddenInputsContainer = document.getElementById('hidden-lrn-inputs');
            const formSection = document.getElementById('violation-form-section');
            const submitBtn = document.getElementById('submit-btn');

            container.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            if (selectedStudents.length > 0) {
                formSection.classList.remove('hidden');
                setTimeout(() => formSection.classList.remove('opacity-0'), 50);
                submitBtn.removeAttribute('disabled');
            } else {
                formSection.classList.add('opacity-0');
                setTimeout(() => formSection.classList.add('hidden'), 300);
                submitBtn.setAttribute('disabled', 'disabled');
            }

            selectedStudents.forEach((student, index) => {
                // Generate UI Card
                container.innerHTML += `
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center font-bold mr-4">
                                ${index + 1}
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-900">${student.name}</div>
                                <div class="text-xs font-medium text-slate-600">LRN: ${student.lrn}</div>
                            </div>
                        </div>
                        <button type="button" onclick="removeStudent('${student.lrn}')" class="px-3 py-1.5 bg-white text-rose-600 hover:bg-rose-50 hover:border-rose-200 rounded-lg text-xs font-bold border border-slate-200 transition-colors shadow-sm">
                            Remove
                        </button>
                    </div>
                `;

                // Generate Hidden Input for Form Submit
                hiddenInputsContainer.innerHTML += `<input type="hidden" name="lrns[]" value="${student.lrn}">`;
            });
        }

        // Auto-loader for when arriving from the Violator Database
        document.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const prefillLrn = urlParams.get('prefill_lrn');

            if (prefillLrn) {
                document.getElementById('lis-search-input').value = prefillLrn;
                await searchLisDatabase();
                const firstResult = document.querySelector('#search-results-list button');
                if (firstResult) firstResult.click();
            }
        });
    </script>
</x-app-layout>
