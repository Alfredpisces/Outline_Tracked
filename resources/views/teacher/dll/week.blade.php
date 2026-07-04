<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md">
                        📅
                    </div>
                    Daily Matrix Workspace
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    <strong class="text-slate-700">{{ $dll->subject }}</strong> • {{ $dll->teaching_dates }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.dll.index') }}"
                    class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">← Back to Dashboard</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50" x-data="dailyMatrixEditor()" x-init="initEditor()">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div
                class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Framework</h3>
                    <p class="text-sm font-bold text-slate-700">Choose your display format for editing.</p>
                </div>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-1 shadow-inner">
                    <button type="button" @click="templateType = 'ILAW'"
                        class="px-5 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                        :class="templateType === 'ILAW' ? 'bg-indigo-600 text-white shadow-sm' :
                            'text-slate-500 hover:bg-slate-100'">
                        ILAW
                    </button>
                    <button type="button" @click="templateType = 'MATATAG'"
                        class="px-5 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                        :class="templateType === 'MATATAG' ? 'bg-blue-600 text-white shadow-sm' :
                            'text-slate-500 hover:bg-slate-100'">
                        MATATAG
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden relative">

                <div
                    class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Daily Execution</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Select a day to
                            update</p>
                    </div>

                    <div
                        class="flex items-center bg-white p-1.5 rounded-2xl border border-slate-200 overflow-x-auto shadow-sm">
                        <template x-for="dateStr in weekDays" :key="dateStr">
                            <button type="button" @click="activeDate = dateStr"
                                class="px-5 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all whitespace-nowrap flex items-center gap-2"
                                :class="activeDate === dateStr ?
                                    'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' :
                                    'text-slate-500 hover:text-slate-800 border border-transparent'">
                                <span x-show="entries[dateStr]?.day_type !== 'regular'" class="text-amber-500">⏸️</span>
                                <span x-text="formatDate(dateStr)"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="p-8 space-y-8" x-show="activeDate">

                    <div x-show="isLocked" x-transition.opacity
                        class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
                        <div class="text-2xl text-amber-500">🔒</div>
                        <div>
                            <h4 class="font-black text-amber-900 text-sm">Lesson Plan Submitted</h4>
                            <p class="text-xs text-amber-700 mt-1">This weekly log is currently under review by your
                                School Head. Your planned activities are locked, but you may still update your
                                end-of-day <strong>Reflections</strong>.</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-4">
                        <div class="flex flex-col gap-1">
                            <h4 class="font-black text-slate-800 text-xl" x-text="formatDate(activeDate)"></h4>
                        </div>

                        <div class="flex items-center gap-3 flex-wrap">
                            <span x-show="saveMessage" x-transition x-text="saveMessage"
                                class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 shadow-sm"></span>

                            <button type="button" @click="pushToNextDay"
                                x-show="!isLocked && weekDays.indexOf(activeDate) < weekDays.length - 1"
                                class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-2">
                                <span>➡️ Push to Next Day</span>
                            </button>

                            <button type="button" @click="saveDay" :disabled="isSaving"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md flex items-center gap-2">
                                <span x-show="!isSaving">💾 Save Day</span>
                                <span x-show="isSaving">Saving...</span>
                            </button>
                        </div>
                    </div>

                    <div x-if="entries[activeDate]">
                        <div
                            class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                            <div class="w-full sm:w-1/2">
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Class
                                    Day Status</label>
                                <select x-model="entries[activeDate].day_type" :disabled="isLocked"
                                    class="w-full rounded-xl text-sm font-bold focus:ring-indigo-500 transition-colors"
                                    :class="isLocked ? 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed' :
                                        'bg-white border-slate-200 text-slate-700'">
                                    <option value="regular">✅ Regular Teaching Day</option>
                                    <option value="holiday">🎉 Holiday (Regular/Special)</option>
                                    <option value="event">🏆 School Event / Intramurals</option>
                                    <option value="leave">✈️ Teacher on Leave / Official Business</option>
                                    <option value="exam">📝 Quarterly Examination</option>
                                    <option value="suspension">⛈️ Class Suspension (Weather/Emergency)</option>
                                </select>
                            </div>

                            <div class="w-full sm:w-1/2" x-show="entries[activeDate].day_type === 'regular'">
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Delivery
                                    Mode</label>
                                <div class="flex border rounded-xl p-1 w-full transition-colors"
                                    :class="isLocked ? 'bg-slate-100 border-transparent' : 'bg-white border-slate-200'">
                                    <button type="button"
                                        @click="!isLocked && (entries[activeDate].delivery_mode = 'f2f')"
                                        class="w-1/2 py-1.5 text-xs font-bold rounded-lg transition-all"
                                        :class="[isLocked ? 'cursor-not-allowed opacity-60' : '', entries[activeDate]
                                            .delivery_mode === 'f2f' ? 'bg-indigo-50 text-indigo-700' :
                                            'text-slate-500 hover:bg-slate-50'
                                        ]">
                                        Face-to-Face
                                    </button>
                                    <button type="button"
                                        @click="!isLocked && (entries[activeDate].delivery_mode = 'modular')"
                                        class="w-1/2 py-1.5 text-xs font-bold rounded-lg transition-all"
                                        :class="[isLocked ? 'cursor-not-allowed opacity-60' : '', entries[activeDate]
                                            .delivery_mode === 'modular' ? 'bg-amber-50 text-amber-700' :
                                            'text-slate-500 hover:bg-slate-50'
                                        ]">
                                        Modular / ADM
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-show="entries[activeDate].day_type !== 'regular'" x-transition.opacity
                            class="bg-amber-50/50 border border-amber-200 rounded-3xl p-8 text-center space-y-4 mt-6">
                            <div class="text-4xl" x-text="getEmojiForDayType(entries[activeDate].day_type)"></div>
                            <h4 class="font-black text-amber-900 text-lg">Non-Teaching Day Logged</h4>
                            <p class="text-sm text-amber-700 max-w-lg mx-auto">Standard lesson plans are bypassed for
                                this day. Please provide the official remarks or reason to maintain DepEd compliance.
                            </p>
                            <div class="max-w-md mx-auto mt-4 text-left">
                                <label
                                    class="block text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Remarks
                                    / Details</label>
                                <textarea x-model="entries[activeDate].non_teaching_reason" rows="3" :disabled="isLocked"
                                    class="w-full rounded-xl focus:ring-amber-500 text-sm transition-colors"
                                    :class="isLocked ? 'bg-amber-100 border-transparent text-amber-600 cursor-not-allowed' :
                                        'bg-white border-amber-200 shadow-sm placeholder-amber-300'"
                                    placeholder="e.g., Independence Day, Attending Division Seminar..."></textarea>
                            </div>
                        </div>

                        <div x-show="entries[activeDate].day_type === 'regular' && templateType === 'ILAW'"
                            x-transition.opacity class="space-y-8 mt-6">
                            <div class="bg-indigo-50/30 border border-indigo-100 rounded-3xl p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-indigo-600 text-xl">💡</span>
                                        <h4 class="font-black text-indigo-900 text-lg">ILAW Procedures</h4>
                                    </div>
                                    <span
                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border"
                                        :class="entries[activeDate].delivery_mode === 'f2f' ?
                                            'bg-indigo-100 text-indigo-700 border-indigo-200' :
                                            'bg-amber-100 text-amber-700 border-amber-200'"
                                        x-text="entries[activeDate].delivery_mode === 'f2f' ? 'In-Person Class' : 'Asynchronous / ADM'">
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">I
                                            - Intentions (Objectives)</label>
                                        <textarea x-model="entries[activeDate].procedure_a" rows="2" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-indigo-100 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2">L
                                            - Learning Experiences</label>
                                        <textarea x-model="entries[activeDate].procedure_c" rows="5" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-indigo-100 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">A
                                            - Assessing Learning</label>
                                        <textarea x-model="entries[activeDate].procedure_e" rows="4" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-emerald-100 focus:ring-emerald-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">W
                                            - Ways Forward (Reflection)</label>
                                        <textarea x-model="entries[activeDate].reflection_notes" rows="3"
                                            class="w-full bg-white border-emerald-300 rounded-xl focus:ring-emerald-500 text-sm shadow-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="entries[activeDate].day_type === 'regular' && templateType === 'MATATAG'"
                            x-transition.opacity class="space-y-8 mt-6" style="display: none;">
                            <div class="bg-blue-50/30 border border-blue-100 rounded-3xl p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-blue-600 text-xl">📝</span>
                                        <h4 class="font-black text-blue-900 text-lg">Teaching Procedures</h4>
                                    </div>
                                    <span
                                        class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border"
                                        :class="entries[activeDate].delivery_mode === 'f2f' ?
                                            'bg-blue-100 text-blue-700 border-blue-200' :
                                            'bg-amber-100 text-amber-700 border-amber-200'"
                                        x-text="entries[activeDate].delivery_mode === 'f2f' ? 'In-Person Class' : 'Asynchronous / ADM'">
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Establishing
                                            Lesson Purpose</label>
                                        <textarea x-model="entries[activeDate].procedure_b" rows="2" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-blue-100 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Activating
                                            Prior Knowledge</label>
                                        <textarea x-model="entries[activeDate].procedure_a" rows="3" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-blue-100 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2 border-t border-blue-100 pt-4 mt-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Developing
                                            Understanding</label>
                                        <textarea x-model="entries[activeDate].procedure_c" rows="5" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-blue-100 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-blue-700 uppercase tracking-wider mb-2">Making
                                            Generalizations</label>
                                        <textarea x-model="entries[activeDate].procedure_d" rows="2" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-blue-100 focus:ring-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-emerald-50/30 border border-emerald-100 rounded-3xl p-6 shadow-sm">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="text-emerald-600 text-xl">📊</span>
                                    <h4 class="font-black text-emerald-900 text-lg">Evaluation & Reflection</h4>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Evaluating
                                            Learning</label>
                                        <textarea x-model="entries[activeDate].procedure_e" rows="3" :disabled="isLocked"
                                            class="w-full rounded-xl text-sm bg-white border-emerald-100 focus:ring-emerald-500"></textarea>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Remarks
                                            & Reflections <span x-show="isLocked"
                                                class="text-emerald-500 font-medium ml-1">(Editable)</span></label>
                                        <textarea x-model="entries[activeDate].reflection_notes" rows="3"
                                            class="w-full bg-white border-emerald-300 rounded-xl focus:ring-emerald-500 text-sm shadow-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <button @click="isScannerOpen = !isScannerOpen" type="button" x-show="!isLocked"
                class="fixed bottom-6 right-24 w-14 h-14 bg-indigo-900 rounded-full shadow-2xl text-white text-xl flex items-center justify-center hover:bg-indigo-950 transition transform hover:scale-105 z-50 border-4 border-white">
                🔎
            </button>

            <div x-show="isScannerOpen" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="fixed bottom-24 right-6 w-80 sm:w-96 h-[600px] bg-slate-50 rounded-3xl shadow-2xl border border-slate-200 flex flex-col z-50 overflow-hidden">

                <div class="bg-indigo-900 px-5 py-4 flex items-center justify-between shadow-md z-10 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white">🔎
                        </div>
                        <div>
                            <h4 class="text-white font-black text-sm">Compliance Scanner</h4>
                            <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-widest">Pre-Flight Check
                            </p>
                        </div>
                    </div>
                    <button @click="isScannerOpen = false" type="button"
                        class="text-indigo-200 hover:text-white transition text-xl">✕</button>
                </div>

                <div class="flex-1 p-5 overflow-y-auto flex flex-col gap-4 relative">
                    <div x-show="!isScanning && !scanResults"
                        class="flex flex-col items-center justify-center h-full text-center space-y-4 opacity-70">
                        <div class="text-5xl">📋</div>
                        <div>
                            <h5 class="font-black text-slate-700">Ready to Review</h5>
                            <p class="text-xs text-slate-500 mt-1 max-w-[200px] mx-auto">Run a scan to check for
                                instructional alignment and DepEd compliance before submitting.</p>
                        </div>
                    </div>

                    <div x-show="isScanning"
                        class="flex flex-col items-center justify-center h-full text-center space-y-4">
                        <div
                            class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin">
                        </div>
                        <div>
                            <h5 class="font-black text-indigo-900">Analyzing Document...</h5>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mt-1">
                                Cross-referencing intentions & assessments</p>
                        </div>
                    </div>

                    <template x-if="scanResults && !isScanning">
                        <div class="space-y-4">
                            <div x-show="schoolHeadFeedback" class="bg-white rounded-2xl p-4 shadow-sm border"
                                :class="scanResults.revisions_addressed ? 'border-emerald-200' : 'border-rose-200'">
                                <div class="flex items-center gap-2 mb-2">
                                    <span x-text="scanResults.revisions_addressed ? '✅' : '❌'"></span>
                                    <h6 class="text-xs font-black uppercase tracking-widest"
                                        :class="scanResults.revisions_addressed ? 'text-emerald-700' : 'text-rose-700'">
                                        School Head Revisions</h6>
                                </div>
                                <p class="text-sm text-slate-600" x-text="scanResults.revision_feedback"></p>
                            </div>

                            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200">
                                <h6 class="text-xs font-black uppercase tracking-widest text-indigo-700 mb-2">
                                    Instructional Alignment</h6>
                                <p class="text-sm text-slate-600" x-text="scanResults.alignment_feedback"></p>
                            </div>

                            <div class="bg-amber-50 rounded-2xl p-4 shadow-sm border border-amber-200">
                                <h6 class="text-xs font-black uppercase tracking-widest text-amber-700 mb-2">Required
                                    Tweaks</h6>
                                <ul class="text-sm text-amber-900 space-y-2 pl-4 list-disc">
                                    <template x-for="item in scanResults.action_items">
                                        <li x-text="item"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-4 bg-white border-t border-slate-100 shrink-0">
                    <button type="button" @click="runComplianceScan" :disabled="isScanning"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                        <span x-show="!isScanning">🚀 Run Pre-Flight Scan</span>
                        <span x-show="isScanning">Scanning...</span>
                    </button>
                </div>
            </div>

            <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
        </div>
    </div>

    <script>
        function dailyMatrixEditor() {
            return {
                weekDays: {!! json_encode($weekDays) !!},
                serverEntries: {!! json_encode($entries) !!},
                dllStatus: '{{ $dll->status }}',
                templateType: 'ILAW',
                entries: {},
                activeDate: '',
                isSaving: false,
                saveMessage: '',

                // PRE-FLIGHT SCANNER STATES ONLY
                isScannerOpen: false,
                isScanning: false,
                scanResults: null,
                schoolHeadFeedback: '{{ addslashes($dll->remarks ?? '') }}',

                get isLocked() {
                    return this.dllStatus === 'submitted' || this.dllStatus === 'checked';
                },

                initEditor() {
                    this.weekDays.forEach(date => {
                        let data = this.serverEntries[date] || {};
                        let dType = 'regular';
                        let dMode = 'f2f';
                        let dReason = '';
                        let procA = data.procedure_a || '';

                        if (procA.startsWith("NON_TEACHING:")) {
                            let parts = procA.substring(13).split('|');
                            dType = parts[0];
                            dReason = parts[1] || '';
                            procA = '';
                        } else if (procA.startsWith("[MODE:modular]")) {
                            dMode = 'modular';
                            procA = procA.replace("[MODE:modular] ", "");
                        } else if (procA.startsWith("[MODE:f2f]")) {
                            dMode = 'f2f';
                            procA = procA.replace("[MODE:f2f] ", "");
                        }

                        this.entries[date] = {
                            day_type: dType,
                            delivery_mode: dMode,
                            non_teaching_reason: dReason,
                            procedure_a: procA,
                            procedure_b: data.procedure_b || '',
                            procedure_c: data.procedure_c || '',
                            procedure_d: data.procedure_d || '',
                            procedure_e: data.procedure_e || '',
                            reflection_notes: data.reflection_notes || ''
                        };
                    });

                    if (this.weekDays.length > 0) {
                        this.activeDate = this.weekDays[0];
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('en-US', {
                        weekday: 'long',
                        month: 'short',
                        day: '2-digit'
                    });
                },

                getEmojiForDayType(type) {
                    const emojis = {
                        holiday: '🎉',
                        event: '🏆',
                        leave: '✈️',
                        exam: '📝',
                        suspension: '⛈️'
                    };
                    return emojis[type] || '📅';
                },

                async pushToNextDay() {
                    let currentIndex = this.weekDays.indexOf(this.activeDate);
                    if (currentIndex >= this.weekDays.length - 1) return;

                    let nextDate = this.weekDays[currentIndex + 1];
                    let formattedNextDate = this.formatDate(nextDate);

                    if (confirm(
                            `Move this lesson plan forward to ${formattedNextDate}?\n\nThis will shift today's planned activities to tomorrow, overwriting anything currently planned for ${formattedNextDate}.`
                        )) {
                        this.entries[nextDate].procedure_a = this.entries[this.activeDate].procedure_a;
                        this.entries[nextDate].procedure_b = this.entries[this.activeDate].procedure_b;
                        this.entries[nextDate].procedure_c = this.entries[this.activeDate].procedure_c;
                        this.entries[nextDate].procedure_d = this.entries[this.activeDate].procedure_d;
                        this.entries[nextDate].procedure_e = this.entries[this.activeDate].procedure_e;

                        this.entries[this.activeDate].procedure_a = '';
                        this.entries[this.activeDate].procedure_b = '';
                        this.entries[this.activeDate].procedure_c = '';
                        this.entries[this.activeDate].procedure_d = '';
                        this.entries[this.activeDate].procedure_e = '';

                        await this.saveDay();
                        this.activeDate = nextDate;
                        this.saveMessage = 'Lesson Moved! Hit save to confirm.';
                        setTimeout(() => this.saveMessage = '', 4000);
                    }
                },

                // UPGRADED: Now loops through the entire week to build a full document context
                buildWeeklyLessonContext() {
                    let fullContext = `[Active Framework: ${this.templateType}]\n\n`;
                    let hasAnyContent = false;

                    // Loop through every day in the week
                    this.weekDays.forEach(dateStr => {
                        let entry = this.entries[dateStr];

                        // Skip if no entry exists or if it's a non-teaching day
                        if (!entry || entry.day_type !== 'regular') return;

                        let dayContent = '';

                        // Grab the text based on the active template
                        if (this.templateType === 'ILAW') {
                            if (entry.procedure_a) dayContent += `Intentions/Goal: ${entry.procedure_a}\n`;
                            if (entry.procedure_c) dayContent += `Planned Activities: ${entry.procedure_c}\n`;
                            if (entry.procedure_e) dayContent += `Current Assessments: ${entry.procedure_e}\n`;
                        } else {
                            if (entry.procedure_b) dayContent += `Lesson Purpose: ${entry.procedure_b}\n`;
                            if (entry.procedure_a) dayContent += `Prior Knowledge: ${entry.procedure_a}\n`;
                            if (entry.procedure_c) dayContent += `Developing Understanding: ${entry.procedure_c}\n`;
                            if (entry.procedure_d) dayContent += `Making Generalizations: ${entry.procedure_d}\n`;
                            if (entry.procedure_e) dayContent += `Evaluation: ${entry.procedure_e}\n`;
                        }

                        // If they actually typed something for this day, add it to the master string
                        if (dayContent.trim() !== '') {
                            hasAnyContent = true;
                            fullContext += `--- ${this.formatDate(dateStr)} ---\n`;
                            fullContext += dayContent + '\n';
                        }
                    });

                    // Return both the massive string and a boolean showing if it's empty
                    return {
                        contextString: fullContext.trim(),
                        hasContent: hasAnyContent
                    };
                },

                async runComplianceScan() {
                    this.isScanning = true;
                    this.scanResults = null;
                    this.isScannerOpen = true;

                    // 1. BATCH SAVE: Force-save every single day to the database first
                    // This ensures the server sees all recent changes across all days!
                    try {
                        for (let dateStr of this.weekDays) {
                            let rawEntry = this.entries[dateStr];
                            if (!rawEntry) continue;

                            let payload = {};
                            if (rawEntry.day_type !== 'regular') {
                                payload = {
                                    procedure_a: "NON_TEACHING:" + rawEntry.day_type + "|" + (rawEntry
                                        .non_teaching_reason || 'No details provided.'),
                                    procedure_b: null,
                                    procedure_c: null,
                                    procedure_d: null,
                                    procedure_e: null,
                                    reflection_notes: rawEntry.reflection_notes
                                };
                            } else {
                                payload = {
                                    procedure_a: "[MODE:" + rawEntry.delivery_mode + "] " + rawEntry.procedure_a,
                                    procedure_b: rawEntry.procedure_b,
                                    procedure_c: rawEntry.procedure_c,
                                    procedure_d: rawEntry.procedure_d,
                                    procedure_e: rawEntry.procedure_e,
                                    reflection_notes: rawEntry.reflection_notes
                                };
                            }

                            // Quietly save each day to the database background
                            await fetch(`/teacher/dll/{{ $dll->id }}/entries/${dateStr}`, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        }
                    } catch (saveError) {
                        console.error("Batch save failed:", saveError);
                        alert("Failed to sync your weekly data before scanning.");
                        this.isScanning = false;
                        return;
                    }

                    // 2. BATCH BUILD: Build the entire week's text context
                    let weeklyData = this.buildWeeklyLessonContext();

                    if (!weeklyData.hasContent) {
                        alert("Your entire weekly matrix appears empty! Please type your lessons before scanning.");
                        this.isScanning = false;
                        return;
                    }

                    // 3. RUN AI EVALUATION ON THE WHOLE BATCH
                    try {
                        let response = await fetch(`/teacher/dll/{{ $dll->id }}/ai/scan`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                lesson_context: weeklyData.contextString,
                                previous_feedback: this.schoolHeadFeedback
                            })
                        });

                        if (!response.ok) throw new Error("Scanner server failure.");
                        this.scanResults = await response.json();

                    } catch (error) {
                        console.error("Scanning Error:", error);
                        alert("The scanner ran into an issue. Check your API key or connection logs.");
                    } finally {
                        this.isScanning = false;
                    }
                },

                async saveDay() {
                    this.isSaving = true;
                    this.saveMessage = '';

                    let rawEntry = this.entries[this.activeDate];
                    let payload = {};

                    if (rawEntry.day_type !== 'regular') {
                        payload = {
                            procedure_a: "NON_TEACHING:" + rawEntry.day_type + "|" + (rawEntry
                                .non_teaching_reason || 'No details provided.'),
                            procedure_b: null,
                            procedure_c: null,
                            procedure_d: null,
                            procedure_e: null,
                            reflection_notes: rawEntry.reflection_notes
                        };
                    } else {
                        payload = {
                            procedure_a: "[MODE:" + rawEntry.delivery_mode + "] " + rawEntry.procedure_a,
                            procedure_b: rawEntry.procedure_b,
                            procedure_c: rawEntry.procedure_c,
                            procedure_d: rawEntry.procedure_d,
                            procedure_e: rawEntry.procedure_e,
                            reflection_notes: rawEntry.reflection_notes
                        };
                    }

                    try {
                        let response = await fetch(`/teacher/dll/{{ $dll->id }}/entries/${this.activeDate}`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (!response.ok) throw new Error("Failed to save entry");
                        this.saveMessage = '✅ Saved Successfully';
                        setTimeout(() => this.saveMessage = '', 3500);
                    } catch (error) {
                        alert("Database Error: Could not save the entry. Please try again.");
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
