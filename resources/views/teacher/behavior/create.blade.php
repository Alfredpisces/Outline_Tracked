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
                {{ __('Log New Incident') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 border-t-4 border-t-rose-500">

            <div class="mb-6 border-b border-slate-100 pb-5">
                <h3 class="text-lg font-black text-slate-800">Behavioral Intake Form</h3>
                <p class="text-sm text-slate-500 mt-1">Search the LIS directory to flag one or multiple students for an
                    active deviation. You may optionally attach photo evidence.</p>
            </div>

            {{-- ⚠️ CRITICAL: enctype="multipart/form-data" is required for file uploads --}}
            <form action="{{ route('teacher.behavior.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reported_by"
                    value="{{ Auth::user()->first_name ?? 'Faculty' }} {{ Auth::user()->last_name ?? '' }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- ======================================================== --}}
                    {{-- 🚀 ALPINE.JS CUSTOM SEARCHABLE MULTI-SELECT WIDGET       --}}
                    {{-- ======================================================== --}}
                    <div class="col-span-1 md:col-span-2 relative" x-data="{
                        search: '',
                        open: false,
                        selectedIds: [],
                        options: [
                            @foreach ($enrolledStudents as $student)
                                {
                                    id: {{ $student->id }},
                                    name: '{{ addslashes($student->last_name) }}, {{ addslashes($student->first_name) }}',
                                    details: 'Grade {{ $student->grade_level }} - {{ $student->section }}'
                                }, @endforeach
                        ],
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(opt =>
                                opt.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                opt.details.toLowerCase().includes(this.search.toLowerCase())
                            );
                        },
                        toggle(id) {
                            if (this.selectedIds.includes(id)) {
                                this.selectedIds = this.selectedIds.filter(i => i !== id);
                            } else {
                                this.selectedIds.push(id);
                            }
                            this.$refs.searchInput.focus();
                        },
                        isSelected(id) {
                            return this.selectedIds.includes(id);
                        },
                        getSelectedItems() {
                            return this.options.filter(opt => this.selectedIds.includes(opt.id));
                        }
                    }">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">
                            Target Student(s) (Search LIS Directory)
                        </label>

                        {{-- Hidden inputs to actually submit the data to Laravel --}}
                        <template x-for="item in getSelectedItems()" :key="item.id">
                            <input type="hidden" name="student_ids[]" :value="item.id">
                        </template>

                        {{-- The Search Box & Selected Pills Display --}}
                        <div @click.away="open = false" class="relative">
                            <div class="w-full min-h-[52px] rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 shadow-sm transition-all focus-within:bg-white focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 flex flex-wrap gap-2 items-center cursor-text"
                                @click="open = true; $refs.searchInput.focus()">

                                {{-- Render Selected Tags (Pills) --}}
                                <template x-for="item in getSelectedItems()" :key="item.id">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-800 text-xs font-bold border border-indigo-200 shadow-sm animate-fade-in-up">
                                        <span x-text="item.name"></span>
                                        <button type="button" @click.stop="toggle(item.id)"
                                            class="text-indigo-400 hover:text-rose-600 transition focus:outline-none">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                {{-- Actual Search Input --}}
                                <input type="text" x-model="search" x-ref="searchInput" @focus="open = true"
                                    @keydown.escape="open = false" placeholder="Type a name to search..."
                                    class="flex-1 bg-transparent border-none p-1 text-sm font-semibold text-slate-700 focus:ring-0 min-w-[150px] placeholder:text-slate-400">

                                {{-- Dropdown Arrow Icon --}}
                                <div
                                    class="text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            {{-- The Dropdown List --}}
                            <div x-show="open" x-transition.opacity.duration.200ms
                                class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto custom-scrollbar"
                                style="display: none;">
                                <template x-if="filteredOptions.length === 0">
                                    <div class="px-4 py-3 text-sm text-slate-500 font-medium">No students found matching
                                        "<span x-text="search" class="font-bold"></span>".</div>
                                </template>

                                <ul class="py-1">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="toggle(option.id)"
                                            class="px-4 py-2.5 cursor-pointer flex items-center justify-between hover:bg-indigo-50 transition-colors"
                                            :class="{ 'bg-slate-50': isSelected(option.id) }">
                                            <div>
                                                <div class="text-sm font-bold"
                                                    :class="isSelected(option.id) ? 'text-indigo-700' : 'text-slate-700'"
                                                    x-text="option.name"></div>
                                                <div class="text-xs text-slate-500" x-text="option.details"></div>
                                            </div>

                                            {{-- Checkmark for selected items --}}
                                            <div x-show="isSelected(option.id)" class="text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        @error('student_ids')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- ======================================================== --}}

                    {{-- Offense Category --}}
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Offense
                            Classification</label>
                        <select name="offense_type" required
                            class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all cursor-pointer">
                            <option value="">-- Select Offense --</option>
                            <option value="Minor Disruption"
                                {{ old('offense_type') == 'Minor Disruption' ? 'selected' : '' }}>Minor Class
                                Disruption</option>
                            <option value="Dress Code Violation"
                                {{ old('offense_type') == 'Dress Code Violation' ? 'selected' : '' }}>Dress Code / ID
                                Violation</option>
                            <option value="Vandalism" {{ old('offense_type') == 'Vandalism' ? 'selected' : '' }}>
                                Vandalism / Property Damage</option>
                            <option value="Physical Altercation"
                                {{ old('offense_type') == 'Physical Altercation' ? 'selected' : '' }}>Physical
                                Altercation / Fighting</option>
                            <option value="Bullying/Harassment"
                                {{ old('offense_type') == 'Bullying/Harassment' ? 'selected' : '' }}>Bullying /
                                Harassment</option>
                            <option value="Academic Dishonesty"
                                {{ old('offense_type') == 'Academic Dishonesty' ? 'selected' : '' }}>Academic
                                Dishonesty</option>
                            <option value="Insubordination"
                                {{ old('offense_type') == 'Insubordination' ? 'selected' : '' }}>Insubordination
                            </option>
                            <option value="Unauthorized Exit"
                                {{ old('offense_type') == 'Unauthorized Exit' ? 'selected' : '' }}>Unauthorized Exit
                            </option>
                        </select>
                        @error('offense_type')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Severity --}}
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Severity
                            Rating</label>
                        <select name="severity" required
                            class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all cursor-pointer">
                            <option value="">-- Assess Severity --</option>
                            <option value="minor" {{ old('severity') == 'minor' ? 'selected' : '' }}>Minor Offense
                            </option>
                            <option value="major" {{ old('severity') == 'major' ? 'selected' : '' }}>Major Offense
                            </option>
                            <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical /
                                Legal Matter</option>
                        </select>
                        @error('severity')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Incident Date --}}
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Date
                            of Occurrence</label>
                        <input type="date" name="incident_date" required
                            value="{{ old('incident_date', date('Y-m-d')) }}"
                            class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 focus:bg-white focus:border-indigo-500 shadow-sm cursor-pointer">
                        @error('incident_date')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Narrative --}}
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Factual
                            Narrative</label>
                        <textarea name="narrative" required rows="4"
                            placeholder="Provide factual details of the incident. If selecting multiple students, this narrative will be attached to all of their records."
                            class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all resize-none">{{ old('narrative') }}</textarea>
                        @error('narrative')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NEW: Evidence Upload --}}
                    <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-5 mt-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Attach Evidence (Optional)
                        </label>
                        <div
                            class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 hover:bg-slate-100 hover:border-indigo-300 transition-colors cursor-pointer relative group">
                            <input type="file" name="evidence_photo" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'JPG, PNG, or GIF (Max 2MB)'">
                            <div
                                class="flex items-center gap-3 text-sm font-semibold text-slate-600 pointer-events-none">
                                <span
                                    class="bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-indigo-600 transition-colors">Choose
                                    File</span>
                                <span id="file-name" class="text-slate-400 truncate max-w-[200px] sm:max-w-md">JPG,
                                    PNG, or GIF (Max 2MB)</span>
                            </div>
                        </div>
                        @error('evidence_photo')
                            <span
                                class="text-[10px] text-rose-500 font-bold mt-1 block uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                </div> {{-- End Grid --}}

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <button type="reset" onclick="window.location.reload()"
                        class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition">Clear
                        Form</button>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest px-8 py-3 rounded-xl shadow-md transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        Submit & Escalate
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
