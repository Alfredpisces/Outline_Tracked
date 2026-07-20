<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800">{{ __('Observation Command Center') }}</h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Manage schedules, assignments, and RPMS-PPST
                    evaluations.</p>
            </div>

            <div class="mt-4 sm:mt-0 flex gap-4">
                <div class="bg-white px-4 py-2 border border-slate-200 rounded-xl shadow-sm text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending</span>
                    <span class="block text-lg font-black text-amber-600">{{ $pendingRequests->count() ?? 0 }}</span>
                </div>
                <div class="bg-white px-4 py-2 border border-slate-200 rounded-xl shadow-sm text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Teachers</span>
                    <span class="block text-lg font-black text-slate-800">{{ $teachers->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-8">

        <!-- Session Status & Errors -->
        @if (session('status'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl font-bold text-sm shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl font-bold text-sm shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- SECTION 1: Pending Scheduling Requests -->
        @if ($pendingRequests->isNotEmpty())
            <div class="bg-white shadow-sm border border-amber-200 rounded-2xl overflow-hidden">
                <div class="p-5 bg-amber-50 border-b border-amber-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-amber-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pending Pre-Observation Requests
                        </h3>
                        <p class="text-xs text-amber-700 mt-1">Assign an eligible observer to approve these schedules.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Teacher</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Proposed Schedule</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Assign Observer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($pendingRequests as $req)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-bold text-slate-900">{{ $req->teacher->first_name }}
                                            {{ $req->teacher->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $req->subject_taught }}
                                            ({{ $req->grade_level }} - {{ $req->section }})</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ $req->observation_date->format('F d, Y') }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($req->time_started)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($req->time_ended)->format('g:i A') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('cot.assign', $req->id) }}" method="POST"
                                            class="flex items-center gap-2">
                                            @csrf
                                            <select name="observer_id" required
                                                class="text-sm border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 w-56">
                                                <option value="" disabled selected>Select Observer...</option>
                                                <option value="{{ Auth::id() }}" class="font-bold text-indigo-700">
                                                    Myself (School Head)</option>
                                                @if (isset($masterTeachers) && $masterTeachers->isNotEmpty())
                                                    <optgroup label="Master Teachers">
                                                        @foreach ($masterTeachers as $master)
                                                            <option value="{{ $master->id }}">
                                                                {{ $master->first_name }} {{ $master->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                            <button type="submit"
                                                class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-black px-4 py-2.5 rounded-lg transition-colors shadow-sm flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- SECTION 2: AI-Powered OCR Scanner -->
        <div
            class="p-6 bg-indigo-50 border border-indigo-100 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <h4 class="text-base font-black text-indigo-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Scan Completed Physical Form
                </h4>
                <p class="text-sm text-indigo-700 mt-1 max-w-xl">Upload a clear photo of a handwritten COT rating sheet.
                    Gemini AI will extract the scores and automatically build the digital record.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto relative z-10">
                <form action="{{ route('school_head.cot.scan') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col sm:flex-row items-center gap-3 w-full">
                    @csrf
                    <!-- Native File/Mobile Camera Input -->
                    <input type="file" name="cot_image" id="cot_image_input"
                        accept="image/jpeg, image/png, image/jpg" capture="environment" required
                        class="w-full sm:w-auto text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-white file:text-indigo-700 hover:file:bg-indigo-100 shadow-sm cursor-pointer transition-colors">

                    <button type="submit" id="scanSubmitBtn"
                        class="w-full sm:w-auto bg-indigo-600 text-white text-xs font-black px-6 py-2.5 rounded-lg shadow-sm hover:bg-indigo-700 transition flex justify-center items-center gap-2 whitespace-nowrap"
                        onclick="this.innerHTML='Scanning with AI...'; this.classList.add('opacity-75', 'cursor-not-allowed');">
                        Extract Scores &rarr;
                    </button>
                </form>

                <!-- Open Webcam Modal Button -->
                <button type="button" onclick="openWebcam()"
                    class="w-full sm:w-auto bg-slate-800 text-white text-xs font-black px-4 py-2.5 rounded-lg shadow-sm hover:bg-slate-700 transition flex justify-center items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Use Webcam
                </button>
            </div>
        </div>

        <!-- SECTION 3: Teacher Observation Roster -->
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden mt-8">
            <div
                class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row xl:justify-between xl:items-center gap-4">
                <h3 class="text-lg font-bold text-slate-800 whitespace-nowrap">Complete Teacher Roster</h3>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
                    <!-- Download PDF Selector -->
                    <form action="{{ route('school_head.cot.blank') }}" method="GET" target="_blank"
                        class="flex w-full sm:w-auto items-center gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
                        <select name="tier" required
                            class="text-xs font-bold text-slate-700 border-slate-300 rounded-lg focus:ring-indigo-500 py-2 pl-3 pr-8 bg-slate-50 cursor-pointer w-full sm:w-auto">
                            <option value="proficient">Proficient (Teacher I-III)</option>
                            <option value="master">Highly Proficient (Master)</option>
                        </select>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download COT form
                        </button>
                    </form>

                    <!-- Search Bar -->
                    <div class="relative w-full sm:w-auto">
                        <input type="text" placeholder="Search teachers..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if ($teachers->isEmpty())
                <div class="p-12 text-center bg-slate-50/30">
                    <p class="text-sm text-slate-500 max-w-sm mx-auto">There are currently no users assigned to the
                        Teacher role in the system.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-wider">
                                    Personnel</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-wider">
                                    DepEd Rank / Tier</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-wider">
                                    Observations (SY)</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($teachers as $teacher)
                                @php
                                    $completedCount = \App\Models\CotObservation::where('teacher_id', $teacher->id)
                                        ->where('status', 'completed')
                                        ->count();
                                @endphp

                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-black text-sm shadow-inner border border-indigo-200/50">
                                                {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900">
                                                    {{ $teacher->last_name }}, {{ $teacher->first_name }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $teacher->department ?? 'General Dept.' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="inline-flex text-xs leading-5 font-bold text-slate-700">{{ $teacher->deped_rank ?? 'Unassigned' }}</span>
                                            @if (str_contains($teacher->deped_rank, 'Master'))
                                                <span
                                                    class="text-[10px] font-black text-violet-700 uppercase tracking-wider">Highly
                                                    Proficient</span>
                                            @else
                                                <span
                                                    class="text-[10px] font-black text-emerald-700 uppercase tracking-wider">Proficient</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-lg font-black {{ $completedCount >= 4 ? 'text-emerald-600' : 'text-slate-800' }}">
                                                {{ $completedCount }} <span
                                                    class="text-sm text-slate-400 font-medium">/ 4</span>
                                            </span>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Completed</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="#"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-white border border-slate-300 rounded-lg font-bold text-xs text-indigo-700 hover:bg-indigo-50 transition shadow-sm"
                                                title="View Past Records">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('school_head.cot.create', $teacher->id) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition shadow-sm">
                                                Observe &rarr;
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- MANUAL TEACHER ASSIGNMENT MODAL -->
    @if (session('show_teacher_modal'))
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="bg-indigo-600 p-5 flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white">Assign Scanned Scores</h3>
                        <p class="text-indigo-100 text-xs font-medium">Scores saved to memory.</p>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm text-slate-600 mb-5 leading-relaxed">
                        The AI successfully extracted the scores from the form, but it could not decipher the cursive
                        handwriting for the teacher's name. <strong>Please select the teacher manually to apply these
                            scores.</strong>
                    </p>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select
                        Teacher</label>
                    <select id="manual_teacher_select"
                        class="w-full border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-3 font-bold text-slate-700 mb-6">
                        @foreach ($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->last_name }}, {{ $t->first_name }}</option>
                        @endforeach
                    </select>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('school_head.cot.index') }}"
                            class="px-5 py-2.5 text-sm text-slate-600 font-bold hover:bg-slate-100 rounded-xl transition-colors">Discard
                            Scan</a>
                        <button
                            onclick="window.location.href='{{ url('/school-head/cot/create') }}/' + document.getElementById('manual_teacher_select').value"
                            class="px-5 py-2.5 text-sm text-white font-black bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                            Apply Scores &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- WEBCAM MODAL -->
    <div id="webcamModal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col">
            <div class="bg-indigo-900 p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📷</span>
                    <h3 class="font-black">Live Document Scanner</h3>
                </div>
                <button type="button" onclick="closeWebcam()"
                    class="text-indigo-200 hover:text-white font-bold text-xl">&times;</button>
            </div>

            <div class="p-4 bg-slate-900 flex justify-center relative">
                <video id="webcamVideo" class="rounded-xl w-full max-w-lg bg-black object-cover" autoplay
                    playsinline></video>
                <canvas id="webcamCanvas" class="hidden"></canvas>

                <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                    <div class="w-3/4 h-5/6 border-2 border-dashed border-indigo-400/50 rounded-lg"></div>
                </div>
            </div>

            <div class="p-5 bg-slate-50 flex justify-between items-center">
                <p class="text-xs text-slate-500 font-bold">Align the COT sheet within the frame.</p>
                <button id="snapButton" type="button" onclick="takeSnapshot()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm px-6 py-3 rounded-xl shadow-md transition-transform active:scale-95 flex items-center gap-2">
                    <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                    Snap & Scan
                </button>
            </div>
        </div>
    </div>

    <script>
        let videoStream = null;

        async function openWebcam() {
            const modal = document.getElementById('webcamModal');
            const video = document.getElementById('webcamVideo');
            modal.classList.remove('hidden');

            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment",
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        }
                    }
                });
                video.srcObject = videoStream;
            } catch (err) {
                alert("Camera access denied or unavailable. Please check your browser permissions.");
                closeWebcam();
            }
        }

        function closeWebcam() {
            document.getElementById('webcamModal').classList.add('hidden');
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }

        function takeSnapshot() {
            const snapBtn = document.getElementById('snapButton');
            snapBtn.innerHTML = 'Scanning with AI...';
            snapBtn.classList.add('opacity-75', 'cursor-not-allowed');

            const video = document.getElementById('webcamVideo');
            const canvas = document.getElementById('webcamCanvas');
            const input = document.getElementById('cot_image_input');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob((blob) => {
                const file = new File([blob], "scanned_cot_" + Date.now() + ".jpg", {
                    type: "image/jpeg"
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;

                closeWebcam();

                // Show loading state on main upload button as well
                const mainSubmitBtn = document.getElementById('scanSubmitBtn');
                if (mainSubmitBtn) {
                    mainSubmitBtn.innerHTML = 'Scanning with AI...';
                    mainSubmitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                }

                input.closest('form').submit();

            }, 'image/jpeg', 0.95);
        }
    </script>
</x-app-layout>
