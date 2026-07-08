<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <form wire:submit.prevent="saveIncident">

        {{-- SECTION 1: REPORT SOURCE & JURISDICTION --}}
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-black uppercase tracking-widest text-indigo-600 mb-6">Jurisdiction & Source</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Report Origin (Omnichannel Intake)</label>
                    <select wire:model="reported_by"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 text-slate-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                        <option value="Counselor (Self-Observed)">Counselor (Self-Observed)</option>
                        <option value="Teacher">Teacher / Faculty Member</option>
                        <option value="Security Guard">Security Guard</option>
                        <option value="SSG Officer">SSG Officer (Manual Relay)</option>
                        <option value="External Citizen">External Citizen / Witness</option>
                    </select>
                </div>

                {{-- Connected to Master Student Database --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Target Student (Master Database)</label>
                    <select wire:model="student_id"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 text-slate-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                        <option value="">-- Select Enrolled Student --</option>
                        @foreach ($enrolledStudents as $student)
                            <option value="{{ $student['id'] }}">
                                {{ $student['name'] }} ({{ $student['grade_section'] }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium leading-tight">Note: Incidents exclusively
                        involving non-enrolled individuals fall outside TrackEd jurisdiction and must be escalated to
                        the LGU/Barangay.</p>
                </div>
            </div>
        </div>

        {{-- SECTION 2: VIOLATION DETAILS (Strictly Behavioral, No Absenteeism) --}}
        <div class="p-8 border-b border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-widest text-indigo-600 mb-6">Incident Diagnostics</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Offense Classification</label>
                    <select wire:model="offense_type"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 text-slate-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                        <option value="">-- Select Primary Offense --</option>
                        <option value="Minor Disruption">Minor Class Disruption</option>
                        <option value="Dress Code Violation">Dress Code / ID Violation</option>
                        <option value="Vandalism">Vandalism / Property Damage</option>
                        <option value="Physical Altercation">Physical Altercation / Fighting</option>
                        <option value="Bullying/Harassment">Bullying / Harassment</option>
                        <option value="Substance Possession">Substance Possession</option>
                        <option value="Unauthorized Exit">Unauthorized Exit (e.g., Over the wall)</option>
                    </select>
                    @error('offense_type')
                        <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Severity Rating</label>
                    <select wire:model="severity"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 text-slate-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                        <option value="">-- Assess Severity --</option>
                        <option value="Low">Low (Warning required)</option>
                        <option value="Moderate">Moderate (Counseling required)</option>
                        <option value="High">High (Parental involvement)</option>
                        <option value="Critical">Critical (Immediate suspension/escalation)</option>
                    </select>
                    @error('severity')
                        <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Incident Description</label>
                    <textarea wire:model="description" rows="4" placeholder="Detail the events as reported by the source..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-3 text-slate-700 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm resize-none"></textarea>
                    @error('description')
                        <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="p-6 bg-slate-50 flex justify-end gap-3 items-center">
            <span wire:loading wire:target="saveIncident" class="text-xs font-bold text-indigo-500 animate-pulse mr-4">
                Encrypting and storing record...
            </span>
            <a href="{{ route('counselor.incidents.index') }}"
                class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition">
                Cancel
            </a>
            <button type="submit" wire:loading.attr="disabled"
                class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Digitize Record
            </button>
        </div>
    </form>
</div>
