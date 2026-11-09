<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl shadow-md">
                        📈
                    </div>
                    {{ __('Professional Growth & COT Diagnostics') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500 font-medium">
                    Digitize Classroom Observation Tools (COT), analyze pedagogical strengths, and track developmental
                    MOVs.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button @click="openCotModal = true" type="button"
                    class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-violet-700 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Upload / Scan COT Sheet
                </button>
                <a href="{{ route('teacher.portfolio.create') ?? '#' }}"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Upload MOV Achievement
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ openCotModal: false, activeTab: 'cot_diagnostics' }" class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 1. EVALUATION PROFILE BANNER --}}
            <div
                class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-700 rounded-3xl p-6 sm:p-8 shadow-sm text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-indigo-200 text-xs font-black uppercase tracking-widest mb-1">RPMS-PPST Career Stage
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-black">
                        {{ auth()->user()->first_name ?? 'Jelani' }} {{ auth()->user()->last_name ?? 'Aguirre' }}
                    </h3>
                    <div class="flex items-center gap-3 mt-3 text-xs font-bold flex-wrap">
                        <span class="bg-white/15 px-3.5 py-1 rounded-full border border-white/20">
                            {{ auth()->user()->deped_rank ?? 'Proficient Teacher I-III' }}
                        </span>
                        <span class="bg-white/15 px-3.5 py-1 rounded-full border border-white/20">
                            {{ auth()->user()->department ?? 'Science' }} Department
                        </span>
                        <span
                            class="bg-amber-400 text-slate-950 px-3.5 py-1 rounded-full font-black uppercase tracking-wider">
                            SY 2026-2027
                        </span>
                    </div>
                </div>

                <div
                    class="bg-white/10 backdrop-blur-sm p-5 rounded-2xl border border-white/20 text-center md:text-right shrink-0">
                    <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest mb-1">Observation
                        Progress</p>
                    <p class="text-2xl font-black text-white">2 / 4 Completed</p>
                    <p class="mt-1 text-xs text-emerald-300 font-semibold">Tier: Proficient (Indicators 1–9)</p>
                </div>
            </div>

            {{-- 2. NAVIGATION TABS --}}
            <div class="flex items-center gap-3 border-b border-slate-200 pb-2">
                <button @click="activeTab = 'cot_diagnostics'"
                    :class="activeTab === 'cot_diagnostics' ? 'bg-indigo-600 text-white shadow-sm' :
                        'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                    📊 COT Diagnostics & Progress Comparison
                </button>
                <button @click="activeTab = 'portfolio_vault'"
                    :class="activeTab === 'portfolio_vault' ? 'bg-indigo-600 text-white shadow-sm' :
                        'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                    🏆 Professional Portfolio Vault (MOVs)
                </button>
            </div>

            {{-- TAB 1: COT DIAGNOSTICS & COMPARATIVE ANALYSIS --}}
            <div x-show="activeTab === 'cot_diagnostics'" class="space-y-8">

                <!-- DIAGNOSTIC SUMMARY (STRENGTHS VS. GROWTH FOCUS) -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                        <div>
                            <span
                                class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-emerald-100 text-emerald-800">
                                Latest Observation Analysis: Quarter 2
                            </span>
                            <h3 class="text-xl font-black text-slate-800 mt-2">Classroom Observation Diagnostic Summary
                            </h3>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Overall Mean
                                Rating</span>
                            <div class="text-3xl font-black text-emerald-600">6.44 / 7.00</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- STRENGTHS -->
                        <div
                            class="bg-emerald-50/50 border border-emerald-200 rounded-2xl p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-600 text-white text-sm font-black">✓</span>
                                    <h4 class="text-sm font-black uppercase tracking-wider text-emerald-900">Pedagogical
                                        Strengths (High Mastery)</h4>
                                </div>
                                <ul class="space-y-2.5 text-xs font-semibold text-emerald-900 pl-2">
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-600 font-bold">•</span>
                                        <span><strong>Indicator 1 (7/7):</strong> Applied content knowledge within and
                                            across curriculum teaching areas.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-600 font-bold">•</span>
                                        <span><strong>Indicator 4 (7/7):</strong> Managed classroom structure to engage
                                            learners individually and in groups.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-emerald-600 font-bold">•</span>
                                        <span><strong>Indicator 7 (7/7):</strong> Planned and sequenced
                                            teaching-learning processes effectively.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4 pt-3 border-t border-emerald-200/60 text-[11px] text-emerald-700 italic">
                                Summary: Exceptional content mastery and structured classroom management routines
                                maintained throughout.
                            </div>
                        </div>

                        <!-- AREAS FOR GROWTH -->
                        <div
                            class="bg-amber-50/50 border border-amber-200 rounded-2xl p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-amber-500 text-white text-sm font-black">!</span>
                                    <h4 class="text-sm font-black uppercase tracking-wider text-amber-900">Areas for
                                        Development (Growth Focus)</h4>
                                </div>
                                <ul class="space-y-2.5 text-xs font-semibold text-amber-900 pl-2">
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span><strong>Indicator 3 (5/7):</strong> Applied teaching strategies to develop
                                            critical and creative thinking.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span><strong>Indicator 8 (5/7):</strong> Adapted differentiated activities for
                                            diverse learner backgrounds.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4 pt-3 border-t border-amber-200/60 text-[11px] text-amber-800">
                                <strong>Actionable Coaching Tip:</strong> Integrate higher-order thinking inquiry
                                prompts and tiered group tasks in Q3.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUARTER-OVER-QUARTER COMPARISON MATRIX -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-800">Quarter-over-Quarter COT Progress Comparison
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Tracking score progression across
                                observation cycles.</p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700">
                            Comparative Baseline: Q1 vs. Q2
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead
                                class="bg-slate-50 border-b border-slate-200 text-[10px] font-black uppercase tracking-widest text-slate-500">
                                <tr>
                                    <th class="px-6 py-4">Indicator Name</th>
                                    <th class="px-6 py-4 text-center">Q1 Score</th>
                                    <th class="px-6 py-4 text-center">Q2 Score</th>
                                    <th class="px-6 py-4 text-center">Progress Delta</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">1. Content Knowledge & Application
                                    </td>
                                    <td class="px-6 py-3.5 text-center font-bold">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-indigo-600">7.0</td>
                                    <td class="px-6 py-3.5 text-center font-black text-emerald-600">+1.0 ▲</td>
                                    <td class="px-6 py-3.5 text-center"><span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Mastered</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">2. Research-Based Teaching
                                        Principles</td>
                                    <td class="px-6 py-3.5 text-center font-bold">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-indigo-600">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-slate-400">0.0 ▬</td>
                                    <td class="px-6 py-3.5 text-center"><span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Consistent</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">3. Critical & Creative Thinking
                                        Strategies</td>
                                    <td class="px-6 py-3.5 text-center font-bold">4.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-indigo-600">5.0</td>
                                    <td class="px-6 py-3.5 text-center font-black text-emerald-600">+1.0 ▲</td>
                                    <td class="px-6 py-3.5 text-center"><span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">Improving</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">4. Classroom Structure &
                                        Management</td>
                                    <td class="px-6 py-3.5 text-center font-bold">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-indigo-600">7.0</td>
                                    <td class="px-6 py-3.5 text-center font-black text-emerald-600">+1.0 ▲</td>
                                    <td class="px-6 py-3.5 text-center"><span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Mastered</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">5. Learner Safety & Physical
                                        Environment</td>
                                    <td class="px-6 py-3.5 text-center font-bold">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-indigo-600">6.0</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-slate-400">0.0 ▬</td>
                                    <td class="px-6 py-3.5 text-center"><span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Consistent</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">6. Positive Discipline & Fair
                                        Treatment</td>
                                    <td class="px-6 py-3.5 text-center font-bold">6.0</td>
                                    <td
                                        class="px-6 py-3.5 text-center font-Here is the clean, streamlined **`resources/views/teacher/portfolio/index.blade.php`** file, formatted to prevent any syntax or looping errors.

```html
<x-app-layout>
    <x-slot name="header">
                                        <div
                                            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h2
                                                    class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                                                    <div
                                                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl shadow-md">
                                                        📈
                                                    </div>
                                                    {{ __('Professional Growth & COT Diagnostics') }}
                                                </h2>
                                                <p class="mt-1 text-sm text-slate-500 font-medium">
                                                    Digitize Classroom Observation Tools (COT), analyze pedagogical
                                                    strengths, and track developmental MOVs.
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <button @click="openCotModal = true" type="button"
                                                    class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-violet-700 gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Upload / Scan COT Sheet
                                                </button>
                                                <a href="{{ route('teacher.portfolio.create') ?? '#' }}"
                                                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700 gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Upload MOV Achievement
                                                </a>
                                            </div>
                                        </div>
                                        </x-slot>

                                        <div x-data="{ openCotModal: false, activeTab: 'cot_diagnostics' }"
                                            class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50">
                                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                                                {{-- 1. EVALUATION PROFILE BANNER --}}
                                                <div
                                                    class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-700 rounded-3xl p-6 sm:p-8 shadow-sm text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
                                                    <div>
                                                        <p
                                                            class="text-indigo-200 text-xs font-black uppercase tracking-widest mb-1">
                                                            RPMS-PPST Career Stage</p>
                                                        <h3 class="text-2xl sm:text-3xl font-black">
                                                            {{ auth()->user()->first_name ?? 'Teacher' }}
                                                            {{ auth()->user()->last_name ?? '' }}
                                                        </h3>
                                                        <div
                                                            class="flex items-center gap-3 mt-3 text-xs font-bold flex-wrap">
                                                            <span
                                                                class="bg-white/15 px-3.5 py-1 rounded-full border border-white/20">
                                                                {{ auth()->user()->deped_rank ?? 'Proficient Teacher I-III' }}
                                                            </span>
                                                            <span
                                                                class="bg-white/15 px-3.5 py-1 rounded-full border border-white/20">
                                                                {{ auth()->user()->department ?? 'Science' }}
                                                                Department
                                                            </span>
                                                            <span
                                                                class="bg-amber-400 text-slate-950 px-3.5 py-1 rounded-full font-black uppercase tracking-wider">
                                                                SY 2026-2027
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="bg-white/10 backdrop-blur-sm p-5 rounded-2xl border border-white/20 text-center md:text-right shrink-0">
                                                        <p
                                                            class="text-indigo-200 text-[10px] font-black uppercase tracking-widest mb-1">
                                                            Observation Progress</p>
                                                        <p class="text-2xl font-black text-white">2 / 4 Completed</p>
                                                        <p class="mt-1 text-xs text-emerald-300 font-semibold">Tier:
                                                            Proficient (Indicators 1–9)</p>
                                                    </div>
                                                </div>

                                                {{-- 2. NAVIGATION TABS --}}
                                                <div class="flex items-center gap-3 border-b border-slate-200 pb-2">
                                                    <button @click="activeTab = 'cot_diagnostics'"
                                                        :class="activeTab === 'cot_diagnostics' ?
                                                            'bg-indigo-600 text-white shadow-sm' :
                                                            'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                                                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                                                        📊 COT Diagnostics & Progress Comparison
                                                    </button>
                                                    <button @click="activeTab = 'portfolio_vault'"
                                                        :class="activeTab === 'portfolio_vault' ?
                                                            'bg-indigo-600 text-white shadow-sm' :
                                                            'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                                                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition">
                                                        🏆 Professional Portfolio Vault (MOVs)
                                                    </button>
                                                </div>

                                                {{-- TAB 1: COT DIAGNOSTICS & COMPARATIVE ANALYSIS --}}
                                                <div x-show="activeTab === 'cot_diagnostics'" class="space-y-8">

                                                    <!-- DIAGNOSTIC SUMMARY (STRENGTHS VS. GROWTH FOCUS) -->
                                                    <div
                                                        class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                                                        <div
                                                            class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
                                                            <div>
                                                                <span
                                                                    class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-emerald-100 text-emerald-800">
                                                                    Latest Observation Analysis: Quarter 2
                                                                </span>
                                                                <h3 class="text-xl font-black text-slate-800 mt-2">
                                                                    Classroom Observation Diagnostic Summary</h3>
                                                            </div>
                                                            <div class="text-left sm:text-right">
                                                                <span
                                                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider">Overall
                                                                    Mean Rating</span>
                                                                <div class="text-3xl font-black text-emerald-600">6.44
                                                                    / 7.00</div>
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                            <!-- STRENGTHS -->
                                                            <div
                                                                class="bg-emerald-50/50 border border-emerald-200 rounded-2xl p-6 flex flex-col justify-between">
                                                                <div>
                                                                    <div class="flex items-center gap-2 mb-3">
                                                                        <span
                                                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-600 text-white text-sm font-black">✓</span>
                                                                        <h4
                                                                            class="text-sm font-black uppercase tracking-wider text-emerald-900">
                                                                            Pedagogical Strengths</h4>
                                                                    </div>
                                                                    <div
                                                                        class="space-y-2 text-xs font-semibold text-emerald-900">
                                                                        <p>• <strong>Indicator 1 (7/7):</strong> Applied
                                                                            content knowledge within and across
                                                                            curriculum areas.</p>
                                                                        <p>• <strong>Indicator 4 (7/7):</strong> Managed
                                                                            classroom structure to engage learners
                                                                            effectively.</p>
                                                                        <p>• <strong>Indicator 7 (7/7):</strong> Planned
                                                                            and sequenced learning processes
                                                                            constructively.</p>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="mt-4 pt-3 border-t border-emerald-200/60 text-[11px] text-emerald-700 italic">
                                                                    Summary: Strong content mastery and classroom
                                                                    routines observed.
                                                                </div>
                                                            </div>

                                                            <!-- AREAS FOR DEVELOPMENT -->
                                                            <div
                                                                class="bg-amber-50/50 border border-amber-200 rounded-2xl p-6 flex flex-col justify-between">
                                                                <div>
                                                                    <div class="flex items-center gap-2 mb-3">
                                                                        <span
                                                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-amber-500 text-white text-sm font-black">!</span>
                                                                        <h4
                                                                            class="text-sm font-black uppercase tracking-wider text-amber-900">
                                                                            Areas for Development</h4>
                                                                    </div>
                                                                    <div
                                                                        class="space-y-2 text-xs font-semibold text-amber-900">
                                                                        <p>• <strong>Indicator 3 (5/7):</strong> Applied
                                                                            strategies to develop critical & creative
                                                                            thinking.</p>
                                                                        <p>• <strong>Indicator 8 (5/7):</strong> Adapted
                                                                            differentiated activities for diverse
                                                                            learner backgrounds.</p>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="mt-4 pt-3 border-t border-amber-200/60 text-[11px] text-amber-800">
                                                                    <strong>Actionable Tip:</strong> Integrate
                                                                    higher-order thinking inquiry prompts and tiered
                                                                    tasks in Q3.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- PROGRESS SUMMARY CARD -->
                                                    <div
                                                        class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <h3 class="text-lg font-black text-slate-800">Quarterly
                                                                    Score Progression (Q1 vs. Q2)</h3>
                                                                <p class="text-xs text-slate-500">Longitudinal growth
                                                                    across 9 RPMS Classroom Indicators.</p>
                                                            </div>
                                                            <span
                                                                class="text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                Average Delta: +0.44 Improvement
                                                            </span>
                                                        </div>

                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                                                            <div
                                                                class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                                                <span
                                                                    class="text-[10px] font-black uppercase text-slate-400">Quarter
                                                                    1 Baseline</span>
                                                                <p class="text-2xl font-black text-slate-800 mt-1">6.00
                                                                    / 7.00</p>
                                                            </div>
                                                            <div
                                                                class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 text-center">
                                                                <span
                                                                    class="text-[10px] font-black uppercase text-indigo-500">Quarter
                                                                    2 Rating</span>
                                                                <p class="text-2xl font-black text-indigo-700 mt-1">
                                                                    6.44 / 7.00</p>
                                                            </div>
                                                            <div
                                                                class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-center">
                                                                <span
                                                                    class="text-[10px] font-black uppercase text-emerald-600">Indicators
                                                                    Improved</span>
                                                                <p class="text-2xl font-black text-emerald-700 mt-1">4
                                                                    of 9</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- TAB 2: PORTFOLIO VAULT (MOVs) --}}
                                                <div x-show="activeTab === 'portfolio_vault'" class="space-y-6">
                                                    <div
                                                        class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <button
                                                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-800 text-white shadow-sm">All
                                                                Records</button>
                                                            <button
                                                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-slate-50 text-slate-600">Seminars
                                                                & Training</button>
                                                            <button
                                                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-slate-50 text-slate-600">Awards</button>
                                                        </div>
                                                        <div class="relative w-full md:w-64">
                                                            <input type="text" placeholder="Search your vault..."
                                                                class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-xs bg-slate-50">
                                                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                                        <div
                                                            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col h-full relative overflow-hidden">
                                                            <div
                                                                class="absolute top-0 left-0 w-full h-1 bg-indigo-500">
                                                            </div>
                                                            <div class="flex justify-between items-start mb-3">
                                                                <span
                                                                    class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-indigo-50 text-indigo-700">Seminars
                                                                    & Training</span>
                                                                <span class="text-xs font-bold text-slate-400">Aug
                                                                    2026</span>
                                                            </div>
                                                            <h4 class="text-base font-black text-slate-800 mb-2">
                                                                Division Training on MATATAG Curriculum</h4>
                                                            <p class="text-xs text-slate-500 mb-6 flex-grow">Completed
                                                                40 hours of pedagogical alignment training for secondary
                                                                educators.</p>
                                                            <span
                                                                class="text-[10px] font-bold text-slate-400 uppercase">Division
                                                                Level</span>
                                                        </div>

                                                        <div
                                                            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col h-full relative overflow-hidden">
                                                            <div
                                                                class="absolute top-0 left-0 w-full h-1 bg-indigo-500">
                                                            </div>
                                                            <div class="flex justify-between items-start mb-3">
                                                                <span
                                                                    class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-indigo-50 text-indigo-700">Awards</span>
                                                                <span class="text-xs font-bold text-slate-400">Jul
                                                                    2026</span>
                                                            </div>
                                                            <h4 class="text-base font-black text-slate-800 mb-2">
                                                                Outstanding Science Teacher (School Level)</h4>
                                                            <p class="text-xs text-slate-500 mb-6 flex-grow">Recognized
                                                                for 100% on-time Daily Lesson Log compliance and high
                                                                COT marks.</p>
                                                            <span
                                                                class="text-[10px] font-bold text-slate-400 uppercase">School
                                                                Level</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- UPLOAD / SCAN COT MODAL --}}
                                            <div x-show="openCotModal"
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                                                style="display: none;">
                                                <div @click.away="openCotModal = false"
                                                    class="bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden p-6 sm:p-8 space-y-6">
                                                    <div
                                                        class="flex items-center justify-between border-b border-slate-100 pb-4">
                                                        <h3
                                                            class="text-lg font-black text-slate-900 flex items-center gap-2">
                                                            <span>📷</span> Digitize COT Rating Sheet (Vision Scan)
                                                        </h3>
                                                        <button @click="openCotModal = false"
                                                            class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div
                                                            class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:bg-slate-50 cursor-pointer">
                                                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-2"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <p class="text-xs font-bold text-slate-700">Upload Photo of
                                                                COT Form or Use Webcam</p>
                                                            <p class="text-[10px] text-slate-400 mt-1">Gemini AI will
                                                                isolate the indicator rubric and extract scores 1–9.</p>
                                                            <input type="file" accept="image/*" class="hidden"
                                                                id="cot-file-input">
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label
                                                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Target
                                                                    Quarter</label>
                                                                <select
                                                                    class="w-full rounded-xl border-slate-300 text-xs font-bold">
                                                                    <option>Quarter 1</option>
                                                                    <option>Quarter 2</option>
                                                                    <option>Quarter 3</option>
                                                                    <option>Quarter 4</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Subject
                                                                    / Grade</label>
                                                                <input type="text" placeholder="e.g. Science 8"
                                                                    class="w-full rounded-xl border-slate-300 text-xs font-semibold">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                        <button @click="openCotModal = false" type="button"
                                                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                                                        <button @click="openCotModal = false" type="button"
                                                            class="px-6 py-2.5 rounded-xl bg-violet-600 text-xs font-black text-white hover:bg-violet-700 shadow-sm">
                                                            Extract & Analyze
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
</x-app-layout>
