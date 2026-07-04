<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-sm shadow-indigo-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight">
                        {{ __('LIS Data Synchronization') }}
                    </h2>
                    <p class="mt-0.5 text-sm text-slate-500 font-medium">
                        Upload official LIS SF1 Excel files, then manage student records per School Year.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-500 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Admin Tool
                </span>

                <a href="{{ route('admin.lis_sync.manage') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md hover:-translate-y-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Manage Directory
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $selectedSY = request('school_year', $defaultSchoolYear ?? '');
    @endphp

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-5 py-4 rounded-r-xl font-bold text-sm shadow-sm flex items-center gap-3 animate-fade-in-down">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 px-5 py-4 rounded-r-xl font-bold text-sm shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- UPLOAD SECTION --}}
        <div
            class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 border-t-4 border-t-indigo-500 transition-all hover:shadow-md">
            <div class="flex items-start justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-xl font-black text-slate-800">
                        Upload SF1 Document
                    </h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-2xl">Upload the official School Form 1 (Excel format)
                        exported directly from the Learner Information System. The system will automatically detect the
                        <strong class="text-slate-700">Grade Level</strong> and <strong
                            class="text-slate-700">Section</strong>.
                    </p>
                </div>
                <div class="hidden sm:flex shrink-0 p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>

            <form action="{{ route('admin.lis_sync.upload') }}" method="POST" enctype="multipart/form-data"
                class="bg-slate-50/60 p-5 rounded-2xl border border-slate-100" id="sf1-upload-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-[200px_1fr_auto] gap-5 lg:items-end">

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Target
                            School Year</label>
                        <select name="school_year" required
                            class="w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 cursor-pointer shadow-sm transition-all">
                            @foreach ($schoolYears as $sy)
                                <option value="{{ $sy }}" {{ $selectedSY === $sy ? 'selected' : '' }}>
                                    SY {{ $sy }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-0">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">SF1
                            Excel File (.xls, .xlsx)</label>

                        <label for="sf1_file" id="sf1-dropzone"
                            class="group flex items-center gap-3 w-full rounded-xl border-2 border-dashed border-slate-300 bg-white px-4 py-2.5 shadow-sm cursor-pointer transition-all hover:border-indigo-400 hover:bg-indigo-50/40">
                            <span
                                class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span id="sf1-filename"
                                    class="block truncate text-sm font-bold text-slate-600 group-hover:text-slate-800">
                                    Choose a file or drag it here&hellip;
                                </span>
                                <span class="block text-[11px] font-semibold text-slate-400">XLS, XLSX, XLSM or
                                    CSV</span>
                            </span>
                            <input id="sf1_file" type="file" name="sf1_file" accept=".xls,.xlsx,.xlsm,.csv" required
                                class="sr-only">
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full lg:w-auto shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Process File
                    </button>
                </div>
            </form>
        </div>

        {{-- SUMMARY & ACTIONS SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Latest Synchronization Stats
                </h3>

                <div class="grid grid-cols-3 gap-4 sm:gap-6">
                    <div
                        class="relative overflow-hidden text-center p-5 bg-gradient-to-b from-emerald-50 to-white rounded-2xl border border-emerald-100 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <svg class="absolute top-2 right-2 w-8 h-8 text-emerald-100" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <span
                            class="relative block text-4xl font-black text-emerald-600 mb-1 tabular-nums">{{ $summary['inserted'] ?? 0 }}</span>
                        <span
                            class="relative text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Inserted</span>
                    </div>

                    <div
                        class="relative overflow-hidden text-center p-5 bg-gradient-to-b from-blue-50 to-white rounded-2xl border border-blue-100 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <svg class="absolute top-2 right-2 w-8 h-8 text-blue-100" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span
                            class="relative block text-4xl font-black text-blue-600 mb-1 tabular-nums">{{ $summary['updated'] ?? 0 }}</span>
                        <span
                            class="relative text-[10px] font-bold text-blue-800 uppercase tracking-wider">Updated</span>
                    </div>

                    <div
                        class="relative overflow-hidden text-center p-5 bg-gradient-to-b from-slate-100 to-white rounded-2xl border border-slate-200 transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        <svg class="absolute top-2 right-2 w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15a3 3 0 100-6 3 3 0 000 6z M9 9l-3 6M15 9l3 6" />
                        </svg>
                        <span
                            class="relative block text-4xl font-black text-slate-500 mb-1 tabular-nums">{{ $summary['skipped'] ?? 0 }}</span>
                        <span
                            class="relative text-[10px] font-bold text-slate-600 uppercase tracking-wider">Skipped</span>
                    </div>
                </div>

                <div
                    class="mt-8 flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                    <span
                        class="font-black text-slate-400 uppercase tracking-widest text-[10px] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Timestamp
                    </span>
                    <span class="font-bold text-slate-700">
                        {{ !empty($summary['last_run']) ? \Carbon\Carbon::parse($summary['last_run'])->format('F d, Y • h:i A') : 'No records processed yet' }}
                    </span>
                </div>
            </div>

            <div
                class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 border-t-4 border-t-slate-800 flex flex-col gap-3 transition-all hover:shadow-md">
                <h3
                    class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    System Actions
                </h3>

                <a href="{{ route('admin.lis_sync.manage') }}"
                    class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white font-black uppercase tracking-widest py-3.5 rounded-xl text-xs hover:bg-slate-800 hover:shadow-md hover:-translate-y-0.5 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    View Directory
                </a>

                <a href="{{ route('admin.lis_sync.reset') }}"
                    class="w-full flex items-center justify-center gap-2 bg-white text-rose-600 border-2 border-rose-100 font-black uppercase tracking-widest py-3 rounded-xl text-xs hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm mt-auto group">
                    <svg class="w-4 h-4 text-rose-400 group-hover:text-rose-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Wipe School Year
                </a>
                <p class="text-center text-[11px] font-semibold text-slate-400">Permanently clears records for the
                    selected year.</p>
            </div>
        </div>

    </div>

    {{-- Lightweight enhancement: shows the chosen filename and supports drag & drop.
         Falls back gracefully — the input works even if this script doesn't run. --}}
    <script>
        (function() {
            var input = document.getElementById('sf1_file');
            var zone = document.getElementById('sf1-dropzone');
            var label = document.getElementById('sf1-filename');
            if (!input || !zone || !label) return;

            function showName(name) {
                label.textContent = name || 'Choose a file or drag it here…';
                label.classList.toggle('text-indigo-700', !!name);
            }

            input.addEventListener('change', function() {
                showName(input.files && input.files[0] ? input.files[0].name : '');
            });

            ['dragenter', 'dragover'].forEach(function(evt) {
                zone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    zone.classList.add('border-indigo-400', 'bg-indigo-50/40');
                });
            });

            ['dragleave', 'drop'].forEach(function(evt) {
                zone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    zone.classList.remove('border-indigo-400', 'bg-indigo-50/40');
                });
            });

            zone.addEventListener('drop', function(e) {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    showName(e.dataTransfer.files[0].name);
                }
            });
        })();
    </script>
</x-app-layout>
