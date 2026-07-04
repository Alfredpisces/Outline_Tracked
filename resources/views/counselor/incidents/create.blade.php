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
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Identify Student Record</h3>
                        <p class="text-sm font-medium text-slate-500 mt-1">Search the LIS staging database to attach the
                            behavioral record.</p>
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

                <div id="selected-student-card"
                    class="hidden mt-6 p-6 bg-emerald-50 border border-emerald-200 rounded-xl flex flex-col sm:flex-row items-center justify-between w-full lg:w-3/4 mx-auto shadow-sm">
                    <div class="flex items-center w-full">
                        <div
                            class="w-12 h-12 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center flex-shrink-0 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-grow">
                            <div class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Target
                                Acquired</div>
                            <div class="text-xl font-black text-slate-900" id="selected-name">Student Name</div>
                            <div class="text-sm font-medium text-slate-600" id="selected-lrn">LRN: 0000</div>
                        </div>
                        <button type="button" onclick="resetSearch()"
                            class="px-4 py-2 bg-white rounded-lg text-sm text-slate-600 font-bold border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm ml-4 whitespace-nowrap">
                            Change Student
                        </button>
                    </div>
                </div>
            </div>

            <div id="violation-form-section"
                class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-10 opacity-0 transition-opacity duration-500">
                <form action="{{ route('counselor.incidents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lrn" id="form-lrn-input">

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
                                <input type="text" name="offense_type" placeholder="e.g. Insubordination, Vandalism"
                                    required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm">
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
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Date
                                    of Occurrence</label>
                                <input type="date" name="incident_date" required
                                    class="block w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 sm:text-sm shadow-sm">
                            </div>

                            <div>
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Factual
                                    Narrative</label>
                                <textarea name="narrative" rows="4"
                                    placeholder="Provide a highly factual, objective account of the behavior..." required
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
                                </select>
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
                        <button type="button" onclick="resetSearch()"
                            class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors mr-0 sm:mr-3 mb-3 sm:mb-0">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-slate-900 hover:bg-rose-600 text-white rounded-lg text-sm font-bold shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
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

                // Prompt for legal documentation details
                notes.placeholder =
                    'Enter the external turnover details (e.g., Police blotter number, DSWD receiving officer, Barangay blotter)...';
            } else {
                // Restore form for normal internal tracking
                followupContainer.style.display = 'block';
                setTimeout(() => {
                    followupContainer.style.opacity = '1';
                }, 10);

                // Reset text placeholder
                notes.placeholder = "Document the student's response, agreements made, and specific next steps...";

                // Only reset dropdowns if they were locked to critical statuses
                if (status.value === 'turned_over') {
                    status.value = 'in_intervention';
                }
                if (intervention.value === 'external_escalation') {
                    intervention.value = '';
                }
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
                    '<div class="px-6 py-8 text-sm font-bold text-rose-500 text-center flex flex-col justify-center items-center"><svg class="animate-spin h-6 w-6 text-rose-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Querying Database...</div>';

                try {
                    const searchUrl = "{{ route('counselor.incidents.search_lis') }}?q=" + encodeURIComponent(input);

                    const response = await fetch(searchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.error || `Server returned status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.length > 0) {
                        resultsList.innerHTML = data.map(student => {
                            const firstName = student.first_name || '';
                            const lastName = student.last_name || '';
                            const fullName = `${lastName}, ${firstName}`.trim();
                            const initial = lastName.charAt(0).toUpperCase() || 'S';

                            return `
                                <button type="button" onclick="selectStudent('${student.lrn}', '${fullName.replace(/'/g, "\\'")}')" class="w-full text-left px-5 py-4 hover:bg-slate-50 flex items-center transition-colors border-b border-slate-100 last:border-0 group focus:outline-none">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-slate-100 border border-slate-200 group-hover:bg-rose-100 group-hover:text-rose-600 flex items-center justify-center text-slate-600 font-bold text-lg mr-4">${initial}</div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-rose-600">${fullName || 'No Name Provided'}</div>
                                        <div class="text-xs font-medium text-slate-500 mt-0.5">LRN: ${student.lrn} <span class="mx-2 text-slate-300">|</span> Grade ${student.grade_level || 'N/A'} - ${student.section || 'N/A'}</div>
                                    </div>
                                </button>
                            `;
                        }).join('');
                    } else {
                        resultsList.innerHTML =
                            '<div class="px-6 py-8 text-sm font-medium text-slate-500 text-center">No behavioral records matched your query.</div>';
                    }
                } catch (error) {
                    resultsList.innerHTML =
                        `<div class="px-6 py-6 text-sm font-bold text-red-500 text-left bg-red-50 m-2 border border-red-200 rounded-lg">System Exception: <span class="font-normal block mt-1">${error.message}</span></div>`;
                }
            } else {
                dropdown.classList.add('hidden');
            }
        }

        function selectStudent(lrn, name) {
            document.getElementById('search-results-dropdown').classList.add('hidden');
            document.getElementById('lis-search-input').value = '';
            document.getElementById('lis-search-input').parentElement.classList.add('hidden');

            document.getElementById('selected-name').innerText = name;
            document.getElementById('selected-lrn').innerText = 'LRN: ' + lrn;
            document.getElementById('selected-student-card').classList.remove('hidden');

            document.getElementById('form-lrn-input').value = lrn;

            const formSection = document.getElementById('violation-form-section');
            formSection.classList.remove('hidden');
            setTimeout(() => {
                formSection.classList.remove('opacity-0');
            }, 50);
        }

        // Moved resetSearch OUTSIDE of DOMContentLoaded so HTML buttons can trigger it
        function resetSearch() {
            document.getElementById('violation-form-section').classList.add('opacity-0');
            setTimeout(() => {
                document.getElementById('violation-form-section').classList.add('hidden');
            }, 300);

            document.getElementById('selected-student-card').classList.add('hidden');
            document.getElementById('lis-search-input').parentElement.classList.remove('hidden');
            document.getElementById('lis-search-input').focus();
            document.getElementById('form-lrn-input').value = '';
        }

        // Auto-loader for when arriving from the Violator Database
        document.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const prefillLrn = urlParams.get('prefill_lrn');

            if (prefillLrn) {
                // 1. Put the LRN in the box
                document.getElementById('lis-search-input').value = prefillLrn;

                // 2. Fetch the student from the database
                await searchLisDatabase();

                // 3. Automatically click the first result so the user skips Step 1 entirely!
                const firstResult = document.querySelector('#search-results-list button');
                if (firstResult) {
                    firstResult.click();
                }
            }
        });
    </script>
</x-app-layout>
