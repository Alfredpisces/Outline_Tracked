<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                    {{ __('Issue Good Moral Certificate') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Check clearance by LRN, or prepare batch printing by grade level / all levels (7–12) with strict
                    school year scoping.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-500 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Clearance
                </span>
                <a href="{{ route('counselor.violators.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm transition hover:bg-slate-50">
                    Behavioral Database
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $scope = $scope ?? request('scope', 'grade');
        $gradeLevels = ['7', '8', '9', '10', '11', '12'];
        $canRunBatch =
            request()->filled('school_year') &&
            (request('scope', 'grade') === 'all' || request()->filled('grade_level'));

        $currentSy = request('school_year') ?? '2025-2026';
    @endphp

    <div class="bg-slate-50 py-8 sm:py-10 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- 1. BATCH PRINTING SECTION --}}
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <h3 class="text-lg font-extrabold text-slate-900">Batch Good Moral Printing</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Generate one combined HTML (print-ready) for all <span
                                    class="font-semibold">eligible</span> students in a specific school year.
                                Students with unresolved major/critical offenses are safely excluded.
                            </p>
                        </div>
                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            <form method="POST" action="{{ route('counselor.good_moral.batch') }}">
                                @csrf
                                <input type="hidden" name="school_year" value="{{ request('school_year') }}">
                                <input type="hidden" name="scope" value="{{ request('scope', 'grade') }}">
                                <input type="hidden" name="grade_level" value="{{ request('grade_level') }}">

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                    @disabled(!$canRunBatch)>
                                    Generate Eligible HTML
                                </button>
                            </form>
                            <form method="POST" action="{{ route('counselor.good_moral.blocked.export') }}">
                                @csrf
                                <input type="hidden" name="school_year" value="{{ request('school_year') }}">
                                <input type="hidden" name="scope" value="{{ request('scope', 'grade') }}">
                                <input type="hidden" name="grade_level" value="{{ request('grade_level') }}">

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-xs font-black uppercase tracking-widest text-rose-700 shadow-sm ring-1 ring-rose-200 transition hover:bg-rose-50 disabled:opacity-60 disabled:cursor-not-allowed"
                                    @disabled(!$canRunBatch)>
                                    Export Blocked PDF
                                </button>
                            </form>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('counselor.good_moral.index') }}" class="mt-6">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="lg:col-span-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500">School
                                    Year</label>
                                <input type="text" name="school_year" value="{{ request('school_year') }}"
                                    placeholder="2025-2026"
                                    class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500">Scope</label>
                                <select name="scope"
                                    class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="grade" {{ $scope === 'grade' ? 'selected' : '' }}>By grade level
                                    </option>
                                    <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>All grade levels
                                        (7–12)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500">Grade
                                    Level</label>
                                <select name="grade_level"
                                    class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 {{ $scope === 'all' ? 'opacity-60' : '' }}"
                                    {{ $scope === 'all' ? 'disabled' : '' }}>
                                    <option value="" {{ request('grade_level') === '' ? 'selected' : '' }}>Select
                                        grade…</option>
                                    @foreach ($gradeLevels as $grade)
                                        <option value="{{ $grade }}"
                                            {{ request('grade_level') === $grade ? 'selected' : '' }}>Grade
                                            {{ $grade }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-800">Apply</button>
                            <a href="{{ route('counselor.good_moral.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">Reset</a>
                        </div>
                    </form>
                </div>
            </section>

            {{-- 2. SINGLE LRN SEARCH --}}
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">Targeted Student Clearance Check</h3>
                        <p class="mt-2 max-w-xl text-sm text-slate-500">
                            Enter the student’s 12-digit LRN and confirm the target School Year to ensure accurate
                            verification.
                        </p>
                    </div>

                    <form action="{{ route('counselor.good_moral.index') }}" method="GET" class="mt-6">
                        <div class="mx-auto flex max-w-2xl flex-col gap-3 sm:flex-row sm:items-stretch">
                            <div class="flex-1">
                                <label for="target_sy" class="sr-only">Target SY</label>
                                <input id="target_sy" type="text" name="target_sy"
                                    value="{{ request('target_sy') ?? $currentSy }}"
                                    class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-center text-sm font-semibold tracking-widest text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    placeholder="2025-2026" required>
                                <p
                                    class="mt-2 text-[10px] text-center font-bold uppercase tracking-widest text-slate-400">
                                    Target SY</p>
                            </div>

                            <div class="flex-[2]">
                                <label for="student_lrn" class="sr-only">Student LRN</label>
                                <input id="student_lrn" type="text" name="student_lrn"
                                    value="{{ request('student_lrn') }}" pattern="\d{12}" maxlength="12"
                                    inputmode="numeric" autocomplete="off"
                                    class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-center text-base font-semibold tracking-widest text-slate-900 shadow-sm placeholder:text-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                                    placeholder="000000000000" required>
                                <p
                                    class="mt-2 text-[10px] text-center font-bold uppercase tracking-widest text-slate-400">
                                    12-Digit LRN</p>
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-emerald-700">
                                Verify & Clear
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- 3. CLEARANCE RESULTS (FIXED Property Check) --}}
            @if ($student)
                @if (isset($student->is_eligible) && $student->is_eligible)
                    <section class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div
                                    class="inline-flex items-center gap-2 rounded-full bg-emerald-200 px-3 py-1 text-xs font-black uppercase tracking-widest text-emerald-900">
                                    <span class="h-2 w-2 rounded-full bg-emerald-700"></span>
                                    Passed Clearance
                                </div>
                                <h3 class="mt-3 truncate text-2xl font-black text-emerald-950">
                                    {{ $student->full_name ?? $student->lrn }}</h3>
                                <p class="mt-2 max-w-2xl text-sm text-emerald-800/80">
                                    Student has zero active major/critical infractions for the selected school year.
                                </p>
                            </div>
                            <form action="{{ route('counselor.good_moral.generate') }}" method="POST"
                                class="shrink-0">
                                @csrf
                                <input type="hidden" name="student_lrn" value="{{ $student->lrn }}">
                                <input type="hidden" name="target_sy" value="{{ request('target_sy') }}">
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-black uppercase tracking-widest text-white shadow-md transition hover:-translate-y-0.5 hover:bg-emerald-700">
                                    Download Certificate
                                </button>
                            </form>
                        </div>
                    </section>
                @else
                    <section class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm sm:p-8">
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-rose-200 px-3 py-1 text-xs font-black uppercase tracking-widest text-rose-900">
                            <span class="h-2 w-2 rounded-full bg-rose-700"></span> Clearance Blocked
                        </div>
                        <h3 class="mt-3 truncate text-2xl font-black text-rose-950">
                            {{ $student->full_name ?? $student->lrn }}</h3>
                        <p class="mt-2 max-w-2xl text-sm text-rose-800/80">
                            This student has an unresolved <span class="font-extrabold underline">major or
                                critical</span> infraction.
                            Resolve the case or seek Principal Override before generating a certificate.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('counselor.incidents.index') }}"
                                class="inline-flex items-center rounded-2xl bg-white px-5 py-3 text-sm font-black uppercase tracking-widest text-rose-700 shadow-sm ring-1 ring-rose-200 transition hover:bg-rose-100/40">
                                Go to Incident Review →
                            </a>
                        </div>
                    </section>
                @endif
            @elseif(request()->filled('student_lrn'))
                <section class="rounded-3xl border border-amber-200 bg-amber-50 p-6 text-center shadow-sm sm:p-8">
                    <h3 class="text-lg font-extrabold text-amber-900 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Student Not Enrolled / Invalid LRN
                    </h3>
                    <p class="mt-2 text-sm text-amber-700">
                        No active enrollment found for LRN <strong>{{ request('student_lrn') }}</strong> in SY
                        <strong>{{ request('target_sy') }}</strong>.
                    </p>
                </section>
            @endif

        </div>
    </div>
</x-app-layout>
