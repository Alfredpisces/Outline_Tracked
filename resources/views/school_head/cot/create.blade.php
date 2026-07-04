<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800">{{ __('Digital COT Evaluation') }}</h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Evaluating: <span
                        class="text-indigo-600 font-bold">{{ $teacher->first_name }} {{ $teacher->last_name }}</span></p>
            </div>
            <a href="{{ route('school_head.cot.index') }}"
                class="text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg shadow-sm transition">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

        @php
            // Calculate AI Scanning Statistics
            $scannedScores = session('scanned_scores', []);
            $isAiMode = !empty($scannedScores);
            $totalIndicators = count($indicators);
            $foundCount = count(array_filter($scannedScores));
            $missingCount = $totalIndicators - $foundCount;
        @endphp

        <!-- Dynamic AI Scanner Banner -->
        @if ($isAiMode)
            @if ($missingCount > 0)
                <!-- Partial Success (Warning) -->
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex items-start gap-4 shadow-sm">
                    <div class="bg-amber-500 text-white p-2 rounded-lg mt-1 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-amber-900 font-black">Partial AI Scan Applied</h3>
                        <p class="text-sm text-amber-800 mt-1">
                            Tesseract OCR extracted <strong>{{ $foundCount }} of {{ $totalIndicators }}</strong>
                            scores from your image. Please fill in the <strong>{{ $missingCount }} missing
                                fields</strong> highlighted below, and verify the rest.
                        </p>
                    </div>
                </div>
            @else
                <!-- Full Success -->
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-start gap-4 shadow-sm">
                    <div class="bg-emerald-600 text-white p-2 rounded-lg mt-1 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-emerald-900 font-black">Complete AI Scan Applied!</h3>
                        <p class="text-sm text-emerald-800 mt-1">
                            Tesseract OCR successfully extracted all <strong>{{ $totalIndicators }}</strong> scores from
                            your image. Please do a quick verification before submitting.
                        </p>
                    </div>
                </div>
            @endif
        @endif

        <form action="{{ route('school_head.cot.store', $teacher->id) }}" method="POST"
            class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            @csrf

            <!-- Teacher Metadata -->
            <div
                class="bg-slate-800 p-6 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">DepEd Rank / Tier</p>
                    <h3 class="text-xl font-black">{{ $teacher->deped_rank ?? 'Unassigned' }}</h3>
                </div>
                <div class="sm:text-right">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Evaluation Required</p>
                    <p class="text-sm font-bold bg-slate-700 px-3 py-1 rounded-lg inline-block">
                        {{ str_contains($teacher->deped_rank, 'Master') ? 'Highly Proficient (4-8)' : 'Proficient (3-7)' }}
                    </p>
                </div>
            </div>

            <!-- Class Information Inputs -->
            <div class="p-6 bg-slate-50 border-b border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Observation
                        Date</label>
                    <input type="date" name="observation_date" required value="{{ date('Y-m-d') }}"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Start
                            Time</label>
                        <input type="time" name="time_started" required
                            class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">End
                            Time</label>
                        <input type="time" name="time_ended" required
                            class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Quarter</label>
                    <select name="quarter" required
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <option value="1">Quarter 1</option>
                        <option value="2">Quarter 2</option>
                        <option value="3">Quarter 3</option>
                        <option value="4">Quarter 4</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subject
                        Taught</label>
                    <input type="text" name="subject_taught" required placeholder="e.g. Science"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Grade
                        Level</label>
                    <input type="text" name="grade_level" required placeholder="e.g. Grade 9"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Section</label>
                    <input type="text" name="section" required placeholder="e.g. Rizal"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
            </div>

            <!-- Rubric Scoring -->
            <div class="p-6">
                <h4 class="text-lg font-black text-slate-800 mb-6 uppercase tracking-wide">Observable Indicators</h4>
                <div class="space-y-4">
                    @php
                        $isMaster = str_contains($teacher->deped_rank, 'Master');
                        $minScore = $isMaster ? 4 : 3;
                        $maxScore = $isMaster ? 8 : 7;
                    @endphp

                    @foreach ($indicators as $num => $indicator)
                        @php
                            $prefilledScore = $scannedScores[$num] ?? null;
                            $hasScanData = !empty($prefilledScore);

                            if ($isAiMode) {
                                if ($hasScanData) {
                                    $rowStyle = 'bg-indigo-50/50 border-indigo-200';
                                    $badgeStyle = 'bg-indigo-600 text-white';
                                    $selectStyle =
                                        'bg-indigo-100 text-indigo-900 border-indigo-300 ring-1 ring-indigo-200';
                                } else {
                                    $rowStyle = 'bg-amber-50 border-amber-300 shadow-sm';
                                    $badgeStyle = 'bg-amber-500 text-white';
                                    $selectStyle = 'bg-white border-amber-400 ring-2 ring-amber-200';
                                }
                            } else {
                                $rowStyle = 'bg-slate-50 border-slate-200';
                                $badgeStyle = 'bg-slate-800 text-white';
                                $selectStyle = 'bg-white border-slate-300';
                            }
                        @endphp

                        <!-- UPDATED: Using CSS Grid to prevent text squishing -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-6 p-5 rounded-xl border {{ $rowStyle }} transition-colors items-center">

                            <!-- Left Side: Indicator Text (Takes up 3/4 of the space) -->
                            <div class="md:col-span-3">
                                <span
                                    class="inline-block {{ $badgeStyle }} text-xs font-bold px-2.5 py-1 rounded mb-2 transition-colors">Indicator
                                    {{ $num }}</span>
                                <p class="text-sm text-slate-800 font-medium leading-relaxed">{{ $indicator }}</p>
                            </div>

                            <!-- Right Side: Dropdown (Takes up 1/4 of the space) -->
                            <div class="md:col-span-1 flex flex-col items-center w-full">
                                <select name="scores[{{ $num }}]" required
                                    class="w-full py-2.5 rounded-lg font-black text-center focus:ring-indigo-500 focus:border-indigo-500 shadow-sm {{ $selectStyle }}">
                                    <option value="" disabled {{ !$hasScanData ? 'selected' : '' }}>Score...
                                    </option>
                                    <option value="{{ $isMaster ? '3' : '2' }}"
                                        {{ $prefilledScore == ($isMaster ? '3' : '2') ? 'selected' : '' }}>NO (Not
                                        Observed)</option>
                                    @for ($i = $minScore; $i <= $maxScore; $i++)
                                        <option value="{{ $i }}"
                                            {{ $prefilledScore == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>

                                @if ($isAiMode)
                                    @if ($hasScanData)
                                        <p
                                            class="text-[10px] font-bold text-indigo-600 mt-2 uppercase flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            AI Scanned
                                        </p>
                                    @else
                                        <p
                                            class="text-[10px] font-bold text-amber-600 mt-2 uppercase flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            Action Required
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-200">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Other Comments /
                    Observations</label>
                <textarea name="comments" rows="3"
                    class="w-full border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm mb-6"
                    placeholder="Provide qualitative feedback here..."></textarea>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-8 py-3 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Submit Final Rating
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>
