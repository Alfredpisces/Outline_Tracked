@php
    $loads = \App\Models\TeachingLoad::where('teacher_id', auth()->id())->get();
    $subjects = $loads->pluck('subject')->unique();
    $grades = $loads->pluck('grade_level')->unique();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                        📘
                    </div>
                    Draft Lesson Log
                </h2>
                <p class="mt-1 text-sm text-slate-500">Official DepEd Formats • Weekly Compilation</p>
            </div>
            <a href="{{ route('teacher.dll.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">Cancel</a>
        </div>
    </x-slot>

    <div class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50" x-data="matatagBuilder()" x-init="initBuilder()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Streamlined 2-Step Wizard Progress Bar -->
            <div class="mb-8 max-w-2xl mx-auto">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 rounded-full -z-10">
                    </div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-600 rounded-full -z-10 transition-all duration-500"
                        :style="'width: ' + ((step - 1) * 100) + '%'"></div>

                    <template x-for="i in 2">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm transition-all duration-300 border-4 border-slate-50 shadow-sm"
                                :class="step >= i ? 'bg-blue-600 text-white' : 'bg-white text-slate-400'">
                                <span x-text="i"></span>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest"
                                :class="step >= i ? 'text-blue-600' : 'text-slate-400'"
                                x-text="['Weekly Blueprint', 'Daily Matrix Setup'][i-1]"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Form Wrapper -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden relative">
                <form id="dllForm" action="{{ route('teacher.dll.store') }}" method="POST"
                    @submit.prevent="submitDLL">
                    @csrf

                    <!-- STEP 1: WEEKLY BLUEPRINT -->
                    <div x-show="step === 1" x-transition.in class="p-8 space-y-8">

                        <!-- FORMAT SELECTOR TOGGLE -->
                        <div
                            class="flex flex-col items-center justify-center bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Select DepEd
                                Framework</h3>
                            <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                                <button type="button" @click="form.template_type = 'ILAW'; autoSave()"
                                    class="px-6 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                                    :class="form.template_type === 'ILAW' ? 'bg-indigo-600 text-white shadow-md' :
                                        'text-slate-500 hover:bg-slate-50'">
                                    ILAW (Simplified)
                                </button>
                                <button type="button" @click="form.template_type = 'MATATAG'; autoSave()"
                                    class="px-6 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                                    :class="form.template_type === 'MATATAG' ? 'bg-blue-600 text-white shadow-md' :
                                        'text-slate-500 hover:bg-slate-50'">
                                    MATATAG (Detailed)
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold mt-3" x-show="form.template_type === 'ILAW'">
                                Using the new 2026-2027 streamlined template.</p>
                            <p class="text-[10px] text-slate-400 font-bold mt-3"
                                x-show="form.template_type === 'MATATAG'">Using the legacy 2024 detailed procedures
                                template.</p>
                        </div>

                        <div class="border-b border-slate-100 pb-4 mt-8">
                            <h3 class="text-xl font-black text-slate-800">I. Curriculum Content & Standards</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Weekly Global
                                Settings</p>
                        </div>

                        <!-- Basic Info Grid -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Subject
                                    Area</label>
                                <select x-model="form.subject" @change="autoSave" required
                                    class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium">
                                    <option value="">-- Select --</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject }}">{{ $subject }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Grade &
                                    Quarter</label>
                                <select x-model="form.grade_quarter" @change="autoSave" required
                                    class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium">
                                    <option value="">-- Select --</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade }} - Q1">{{ $grade }} - Q1</option>
                                        <option value="{{ $grade }} - Q2">{{ $grade }} - Q2</option>
                                        <option value="{{ $grade }} - Q3">{{ $grade }} - Q3</option>
                                        <option value="{{ $grade }} - Q4">{{ $grade }} - Q4</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Week
                                    Start (Mon)</label>
                                <input type="date" x-model="form.week_start" @change="autoSave" required
                                    class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Week
                                    End (Fri)</label>
                                <input type="date" x-model="form.week_end" @change="autoSave" required
                                    class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium">
                            </div>
                        </div>

                        <!-- Standards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">A.
                                    Content Standards</label>
                                <textarea x-model="form.content_standard" @input="autoSave" rows="2"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">B.
                                    Performance Standards</label>
                                <textarea x-model="form.performance_standard" @input="autoSave" rows="2"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">C.
                                    Learning Competencies</label>
                                <textarea x-model="form.competencies" @input="autoSave" rows="2"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">D.
                                    Learning Objectives</label>
                                <textarea x-model="form.objectives" @input="autoSave" rows="2"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">E.
                                    Content (Topic)</label>
                                <input type="text" x-model="form.content" @input="autoSave"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">F.
                                    Integration (Values/Cross-curriculum)</label>
                                <input type="text" x-model="form.integration" @input="autoSave"
                                    class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-6 mt-6">
                            <h3 class="text-xl font-black text-slate-800 mb-4">II. Learning Resources</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">References
                                        (TG/LM Pages)</label>
                                    <textarea x-model="form.references" @input="autoSave" rows="2"
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Other
                                        Materials (Links, Props)</label>
                                    <textarea x-model="form.other_resources" @input="autoSave" rows="2"
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: DAILY MATRIX PREVIEW/SETUP -->
                    <div x-show="step === 2" x-transition.in class="p-8 space-y-6" style="display: none;">

                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-800">Daily Execution</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Pre-fill
                                    activities (optional) or save and edit day-by-day later.</p>
                            </div>
                            <div
                                class="flex items-center bg-slate-100 p-1.5 rounded-2xl border border-slate-200 overflow-x-auto">
                                <template x-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']">
                                    <button type="button" @click="activeDay = day"
                                        class="px-5 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all whitespace-nowrap"
                                        :class="activeDay === day ? 'bg-white text-blue-600 shadow-sm' :
                                            'text-slate-500 hover:text-slate-800'"
                                        x-text="day"></button>
                                </template>
                            </div>
                        </div>

                        <!-- ILAW FRAMEWORK UI -->
                        <div x-show="form.template_type === 'ILAW'" class="space-y-8" x-transition.opacity>
                            <div class="bg-indigo-50/30 border border-indigo-100 rounded-3xl p-6">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="text-indigo-600 text-xl">💡</span>
                                    <h4 class="font-black text-indigo-900 text-lg">ILAW Procedures <span
                                            class="text-indigo-400 font-medium text-sm ml-2"
                                            x-text="'- ' + activeDay"></span></h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">I
                                            - Intentions</label>
                                        <textarea x-model="form.days[activeDay].intentions" @input="autoSave" rows="2"
                                            class="w-full bg-white border-indigo-100 rounded-xl focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">L
                                            - Learning Experiences</label>
                                        <textarea x-model="form.days[activeDay].learning_experiences" @input="autoSave" rows="5"
                                            class="w-full bg-white border-indigo-100 rounded-xl focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">A
                                            - Assessing Learning</label>
                                        <textarea x-model="form.days[activeDay].assessing_learning" @input="autoSave" rows="4"
                                            class="w-full bg-white border-emerald-100 rounded-xl focus:ring-emerald-500 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">W
                                            - Ways Forward</label>
                                        <textarea x-model="form.days[activeDay].ways_forward" @input="autoSave" rows="4"
                                            class="w-full bg-white border-emerald-100 rounded-xl focus:ring-emerald-500 text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MATATAG FRAMEWORK UI -->
                        <div x-show="form.template_type === 'MATATAG'" class="space-y-8" x-transition.opacity
                            style="display: none;">
                            <div class="bg-blue-50/30 border border-blue-100 rounded-3xl p-6">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="text-blue-600 text-xl">📝</span>
                                    <h4 class="font-black text-blue-900 text-lg">Teaching Procedures <span
                                            class="text-blue-400 font-medium text-sm ml-2"
                                            x-text="'- ' + activeDay"></span></h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Activating
                                            Prior Knowledge</label>
                                        <textarea x-model="form.days[activeDay].act_prior_knowledge" @input="autoSave" rows="3"
                                            class="w-full bg-white border-blue-100 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Establishing
                                            Lesson Purpose</label>
                                        <textarea x-model="form.days[activeDay].est_purpose" @input="autoSave" rows="3"
                                            class="w-full bg-white border-blue-100 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Developing
                                            & Deepening Understanding</label>
                                        <textarea x-model="form.days[activeDay].dev_understanding" @input="autoSave" rows="5"
                                            class="w-full bg-white border-blue-100 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Making
                                            Generalizations</label>
                                        <textarea x-model="form.days[activeDay].generalizations" @input="autoSave" rows="2"
                                            class="w-full bg-white border-blue-100 rounded-xl focus:ring-blue-500 text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-emerald-50/30 border border-emerald-100 rounded-3xl p-6">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="text-emerald-600 text-xl">📊</span>
                                    <h4 class="font-black text-emerald-900 text-lg">Evaluation & Reflection</h4>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Evaluating
                                            Learning (Assessment)</label>
                                        <textarea x-model="form.days[activeDay].eval_learning" @input="autoSave" rows="2"
                                            class="w-full bg-white border-emerald-100 rounded-xl focus:ring-emerald-500 text-sm"></textarea>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Teacher's
                                                Remarks</label>
                                            <textarea x-model="form.days[activeDay].remarks" @input="autoSave" rows="4"
                                                class="w-full bg-white border-emerald-100 rounded-xl focus:ring-emerald-500 text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Teacher's
                                                Reflections</label>
                                            <textarea x-model="form.days[activeDay].reflection" @input="autoSave" rows="4"
                                                class="w-full bg-white border-emerald-100 rounded-xl focus:ring-emerald-500 text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WIZARD CONTROLS -->
                    <div class="bg-slate-50 border-t border-slate-200 p-6 flex items-center justify-between">
                        <button type="button" @click="step--" x-show="step === 2"
                            class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-sm">
                            ← Back
                        </button>
                        <div x-show="step === 1"></div>

                        <div class="flex items-center gap-4 flex-wrap justify-end">
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Auto-Saved
                            </span>

                            <!-- SAVE DRAFT & EXIT BUTTON (Available on Step 1) -->
                            <button type="button" @click="saveDraft($event)" x-show="step === 1"
                                class="px-6 py-2.5 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-sm">
                                Save Draft & Exit
                            </button>

                            <!-- NEXT STEP BUTTON -->
                            <button type="button" @click="step++" x-show="step === 1"
                                class="px-6 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md">
                                Next: Daily Matrix →
                            </button>

                            <!-- FINAL SUBMIT BUTTON (No AI Check Required) -->
                            <button type="submit" x-show="step === 2"
                                class="px-8 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/30">
                                Save & Initialize Log
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function matatagBuilder() {
            const emptyDay = () => ({
                act_prior_knowledge: '',
                est_purpose: '',
                dev_understanding: '',
                generalizations: '',
                eval_learning: '',
                remarks: '',
                reflection: '',
                intentions: '',
                learning_experiences: '',
                assessing_learning: '',
                ways_forward: ''
            });

            return {
                step: 1,
                activeDay: 'Monday',

                form: {
                    template_type: 'ILAW',
                    subject: '',
                    grade_quarter: '',
                    week_start: '',
                    week_end: '',
                    content_standard: '',
                    performance_standard: '',
                    competencies: '',
                    objectives: '',
                    content: '',
                    integration: '',
                    references: '',
                    other_resources: '',
                    days: {
                        Monday: emptyDay(),
                        Tuesday: emptyDay(),
                        Wednesday: emptyDay(),
                        Thursday: emptyDay(),
                        Friday: emptyDay()
                    }
                },

                initBuilder() {
                    try {
                        const saved = localStorage.getItem('tracked_matatag_draft');
                        if (saved) {
                            let parsedData = JSON.parse(saved);
                            if (parsedData && parsedData.days) {
                                if (!parsedData.template_type) parsedData.template_type = 'MATATAG';
                                this.form = parsedData;
                            } else {
                                localStorage.removeItem('tracked_matatag_draft');
                            }
                        }
                    } catch (error) {
                        localStorage.removeItem('tracked_matatag_draft');
                    }
                },

                autoSave() {
                    localStorage.setItem('tracked_matatag_draft', JSON.stringify(this.form));
                },

                getPayload(actionType) {
                    return {
                        action: actionType,
                        template_type: this.form.template_type,
                        subject: this.form.subject,
                        grade_quarter: this.form.grade_quarter,
                        week_start_date: this.form.week_start,
                        week_end_date: this.form.week_end,
                        content_standard: this.form.content_standard,
                        performance_standard: this.form.performance_standard,
                        competencies: this.form.competencies,
                        objectives: this.form.objectives,
                        content: this.form.content,
                        integration: this.form.integration,
                        references: this.form.references,
                        other_resources: this.form.other_resources,

                        entries: Object.keys(this.form.days).map((dayName, index) => {
                            let baseDate = new Date(this.form.week_start || new Date());
                            baseDate.setDate(baseDate.getDate() + index);
                            let dateStr = baseDate.toISOString().split('T')[0];
                            let dayData = this.form.days[dayName];

                            if (this.form.template_type === 'ILAW') {
                                return {
                                    entry_date: dateStr,
                                    procedure_a: dayData.intentions,
                                    procedure_c: dayData.learning_experiences,
                                    procedure_e: dayData.assessing_learning,
                                    reflection: dayData.ways_forward
                                };
                            }
                            return {
                                entry_date: dateStr,
                                procedure_a: dayData.act_prior_knowledge,
                                procedure_b: dayData.est_purpose,
                                procedure_c: dayData.dev_understanding,
                                procedure_d: dayData.generalizations,
                                procedure_e: dayData.eval_learning,
                                remarks: dayData.remarks,
                                reflection: dayData.reflection
                            };
                        })
                    };
                },

                async saveDraft(event) {
                    const btn = event.currentTarget;
                    let originalText = btn.innerText;
                    btn.innerText = "Saving Draft...";
                    btn.disabled = true;

                    try {
                        let response = await fetch('{{ route('teacher.dll.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.getPayload('draft'))
                        });

                        if (!response.ok) throw new Error("Could not save draft.");

                        localStorage.removeItem('tracked_matatag_draft');
                        window.location.href = '{{ route('teacher.dll.index') }}';
                    } catch (error) {
                        alert("Error saving draft. Make sure 'Week Start' is filled out.");
                        btn.innerText = originalText;
                        btn.disabled = false;
                    }
                },

                async submitDLL() {
                    const btn = document.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.innerText = "Saving Log...";
                        btn.disabled = true;
                    }

                    try {
                        let response = await fetch('{{ route('teacher.dll.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.getPayload(
                                'draft')) // Changed 'submit' to 'draft' so it stays a draft initially
                        });

                        if (!response.ok) throw new Error("Database Validation Error");

                        localStorage.removeItem('tracked_matatag_draft');
                        window.location.href = '{{ route('teacher.dll.index') }}';

                    } catch (error) {
                        alert("FAILED TO SAVE:\n\n" + error.message);
                        if (btn) {
                            btn.innerText = "Save & Initialize Log";
                            btn.disabled = false;
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
