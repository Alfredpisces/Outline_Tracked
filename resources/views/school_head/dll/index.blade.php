<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl shadow-md">
                        📊
                    </div>
                    Instructional Supervision
                </h2>
                <p class="mt-1 text-sm text-slate-500">Manage Faculty Compliance and Lesson Logs</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Filter:</span>
                    <select name="sy" onchange="this.form.submit()"
                        class="text-sm font-bold text-slate-700 bg-white border-slate-200 rounded-xl focus:ring-indigo-500 shadow-sm py-2 px-4">
                        <option value="2026-2027" {{ $activeSY == '2026-2027' ? 'selected' : '' }}>SY 2026-2027</option>
                        <option value="2025-2026" {{ $activeSY == '2025-2026' ? 'selected' : '' }}>SY 2025-2026</option>
                    </select>
                </form>

                <div class="h-6 w-px bg-slate-300 hidden sm:block"></div>

                <a href="{{ route('school_head.dll.export') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-md shadow-emerald-600/20">
                    📥 Download Excel
                </a>
                <a href="{{ route('school_head.dashboard') }}"
                    class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 min-h-[calc(100vh-10rem)] bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($groupedLogs as $teacherName => $logs)
                    @php
                        // PHASE 4: DSS Ranking Calculations
                        $totalLogs = $logs->count();
                        $pending = $logs->where('status', 'submitted')->count();
                        $checked = $logs->where('status', 'checked')->count();
                        $needsRevision = $logs->where('status', 'needs_revision')->count();

                        // Calculate Compliance Score (100% if everything is Checked)
                        $complianceScore = $totalLogs > 0 ? round(($checked / $totalLogs) * 100) : 0;

                        // Determine border color based on pending items
                        $borderColor =
                            $pending > 0 ? 'border-amber-400 shadow-amber-100' : 'border-slate-200 shadow-slate-200/50';
                    @endphp

                    <div x-data="{ focusMode: false }"
                        class="bg-white rounded-2xl shadow-lg {{ $borderColor }} border transition-all duration-300">

                        <div class="p-6 cursor-pointer hover:bg-slate-50 rounded-2xl transition-colors"
                            @click="focusMode = true">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-xl border-2 border-white shadow-sm">
                                        {{ substr($teacherName, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-900">{{ $teacherName }}</h3>
                                        <div class="flex items-center gap-3 mt-1">
                                            @if ($pending > 0)
                                                <span
                                                    class="flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                    <span
                                                        class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    {{ $pending }} Pending Review
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    All Clear
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right flex flex-col items-end">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Compliance</span>
                                    <div class="radial-progress text-sm font-black {{ $complianceScore >= 80 ? 'text-emerald-600' : ($complianceScore >= 50 ? 'text-amber-500' : 'text-rose-500') }}"
                                        style="--value:{{ $complianceScore }}; --size:2.5rem; --thickness: 4px;">
                                        {{ $complianceScore }}%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="focusMode" style="display: none;"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-8">

                            <div x-show="focusMode" x-transition.opacity
                                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="focusMode = false">
                            </div>

                            <div x-show="focusMode" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                class="relative w-full max-w-6xl max-h-full bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden border border-slate-200">

                                <div
                                    class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-sm">
                                            {{ substr($teacherName, 0, 1) }}
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-black text-slate-800">Focus Mode:
                                                {{ $teacherName }}</h2>
                                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                                                Instructional Supervision Checklist</p>
                                        </div>
                                    </div>
                                    <button @click="focusMode = false"
                                        class="flex items-center gap-2 px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-sm">
                                        <span>✕</span> Close
                                    </button>
                                </div>

                                <div class="p-6 overflow-y-auto bg-white flex-1">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-cyan-50 border-b-2 border-cyan-200">
                                                <th class="px-5 py-4 font-black text-slate-700 text-sm">Week / Subject
                                                </th>
                                                <th class="px-5 py-4 font-black text-slate-700 text-sm text-center">
                                                    Status</th>
                                                <th class="px-5 py-4 font-black text-slate-700 text-sm">Supervision
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logs as $dll)
                                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors"
                                                    x-data="reviewAssistant('{{ route('school_head.dll.ai.brief', $dll->id) }}')">

                                                    <td class="px-5 py-5 align-top">
                                                        <div class="font-black text-lg text-slate-900">
                                                            {{ $dll->subject }}</div>
                                                        <div class="text-slate-600 font-bold mb-1">Grade:
                                                            {{ $dll->grade_quarter }}</div>
                                                        <div class="text-sm text-slate-500 mt-0.5">
                                                            @if ($dll->week_start_date)
                                                                {{ \Carbon\Carbon::parse($dll->week_start_date)->format('M d') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($dll->week_end_date)->format('M d, Y') }}
                                                            @else
                                                                {{ $dll->teaching_dates }}
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('school_head.dll.show', $dll) }}"
                                                            target="_blank"
                                                            class="mt-3 inline-flex items-center gap-1 text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition-colors">
                                                            📄 Read Full Matrix
                                                        </a>
                                                    </td>

                                                    <td class="px-5 py-5 align-top text-center">
                                                        @if ($dll->status === 'submitted')
                                                            <span
                                                                class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-lg text-xs font-black uppercase tracking-widest border border-amber-200 shadow-sm">Pending
                                                                Review</span>
                                                        @elseif($dll->status === 'checked')
                                                            <span
                                                                class="inline-block px-4 py-2 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-black uppercase tracking-widest border border-emerald-200 shadow-sm">Checked</span>
                                                        @elseif($dll->status === 'needs_revision')
                                                            <span
                                                                class="inline-block px-4 py-2 bg-rose-100 text-rose-800 rounded-lg text-xs font-black uppercase tracking-widest border border-rose-200 shadow-sm">Revision</span>
                                                        @else
                                                            <span
                                                                class="inline-block px-4 py-2 bg-slate-100 text-slate-800 rounded-lg text-xs font-black uppercase tracking-widest border border-slate-200">{{ $dll->status }}</span>
                                                        @endif
                                                    </td>

                                                    <td class="px-5 py-5 align-top">
                                                        @if ($dll->status === 'submitted')
                                                            <button type="button" @click="generateBrief"
                                                                :disabled="isGenerating"
                                                                class="w-full mb-4 text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 px-4 py-2 rounded-xl font-black uppercase tracking-widest disabled:opacity-50 transition-colors shadow-sm">
                                                                <span x-show="!isGenerating">🤖 Run Smart Engine
                                                                    Pre-Check</span>
                                                                <span x-show="isGenerating">Analyzing Matrix...</span>
                                                            </button>

                                                            <div x-show="aiBrief" x-transition
                                                                class="text-sm text-slate-700 bg-blue-50/50 p-4 rounded-xl border border-blue-100 mb-4 leading-relaxed shadow-inner">
                                                                <p x-text="aiBrief"></p>
                                                            </div>

                                                            <div
                                                                class="flex flex-col gap-3 bg-slate-100/50 p-4 rounded-xl border border-slate-200">
                                                                <form method="POST"
                                                                    action="{{ route('school_head.dll.return', $dll) }}"
                                                                    class="flex flex-col gap-2">
                                                                    @csrf @method('PUT')
                                                                    <label
                                                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500">Provide
                                                                        Technical Assistance:</label>
                                                                    <textarea name="review_feedback" rows="2" required placeholder="Type your feedback here..."
                                                                        x-model="suggestedFeedback" class="w-full rounded-lg border-slate-300 text-sm focus:ring-amber-500"></textarea>
                                                                    <button
                                                                        class="w-full rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 border border-amber-300 px-4 py-2 text-xs font-black uppercase tracking-widest transition-colors shadow-sm">
                                                                        Send Technical Assistance
                                                                    </button>
                                                                </form>

                                                                <div class="relative flex py-2 items-center">
                                                                    <div class="flex-grow border-t border-slate-300">
                                                                    </div>
                                                                    <span
                                                                        class="flex-shrink-0 mx-4 text-slate-400 text-xs font-bold uppercase">OR</span>
                                                                    <div class="flex-grow border-t border-slate-300">
                                                                    </div>
                                                                </div>

                                                                <form method="POST"
                                                                    action="{{ route('school_head.dll.approve', $dll) }}">
                                                                    @csrf @method('PUT')
                                                                    <button
                                                                        class="w-full rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-3 text-sm font-black uppercase tracking-widest shadow-md transition-transform active:scale-95">
                                                                        ✓ Sign & Mark as Checked
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                                                                <span class="text-2xl block mb-1">👍</span>
                                                                <div
                                                                    class="text-xs font-black uppercase tracking-widest text-slate-400">
                                                                    Action Completed</div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
                        <span class="text-5xl block mb-4 opacity-50">🎓</span>
                        <h3 class="text-lg font-black text-slate-800">No Faculty Data for this School Year</h3>
                        <p class="text-sm text-slate-500 mt-1">Try selecting a different School Year from the filter
                            above.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <style>
        .radial-progress {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: var(--size);
            height: var(--size);
            border-radius: 50%;
            background: conic-gradient(currentColor calc(var(--value) * 1%), #f1f5f9 0);
        }

        .radial-progress::before {
            content: "";
            position: absolute;
            inset: var(--thickness);
            border-radius: 50%;
            background-color: white;
        }

        .radial-progress>* {
            position: relative;
            z-index: 1;
        }
    </style>

    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">

    <script>
        function reviewAssistant(apiUrl) {
            return {
                endpoint: apiUrl,
                isGenerating: false,
                aiBrief: '',
                suggestedFeedback: '',

                async generateBrief() {
                    this.isGenerating = true;
                    this.aiBrief = '';

                    try {
                        let response = await fetch(this.endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                'Accept': 'application/json'
                            }
                        });

                        // 1. Check if Laravel threw a 500 Server Error
                        if (!response.ok) {
                            let errorData = await response.json();
                            throw new Error(errorData.message || "Laravel Server Error " + response.status);
                        }

                        // 2. Parse the successful JSON
                        let data = await response.json();
                        this.aiBrief = data.brief;

                        if (data.suggested_feedback) {
                            this.suggestedFeedback = data.suggested_feedback;
                        }

                    } catch (error) {
                        console.error("Smart Engine Error:", error);
                        // Print the exact error to the UI so we can see it!
                        this.aiBrief = "⚠️ Error: " + error.message +
                            ". (Check your terminal or browser console for details).";
                    } finally {
                        this.isGenerating = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
