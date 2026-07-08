<x-app-layout>
    {{-- Centralized Alpine State for Modals and Excel Modes --}}
    <div class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50" x-data="{ showProfileModal: false, showExcelModal: false, excelMode: 'download' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                            📘
                        </div>
                        Daily Lesson Logs
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Manage your MATATAG and ILAW weekly lesson plans across all your assigned JHS grade levels.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('teacher.dll.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-700">
                        + Draft Online
                    </a>

                    {{-- NEW: Excel Offline Drafting Button --}}
                    <button @click="showExcelModal = true"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-emerald-700">
                        📊 Draft through Excel
                    </button>
                </div>
            </div>

            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div
                    class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('status'))
                <div
                    class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                    ✅ {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-sm font-bold shadow-sm">
                    <div class="mb-2 flex items-center gap-2">⚠️ Please fix the following:</div>
                    <ul class="list-disc pl-8 space-y-1 text-rose-600">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- TEACHING LOAD PROFILES --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">
                        My Teaching Load Profiles
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse ($profiles as $profile)
                        <div
                            class="rounded-2xl p-5 shadow-sm relative overflow-hidden cursor-default transition-all bg-white border border-slate-200 hover:border-blue-400 hover:shadow-md group">
                            <div class="flex justify-between items-start mb-3">
                                <span
                                    class="px-2 py-1 text-[9px] font-black uppercase tracking-widest rounded bg-slate-100 text-slate-500">
                                    Subject
                                </span>
                                <span class="text-xl">
                                    {{ $profile->subject == 'Science' ? '🧬' : ($profile->subject == 'Mathematics' ? '➗' : '📘') }}
                                </span>
                            </div>

                            <h4 class="font-black text-slate-900 text-lg">{{ $profile->subject }}</h4>
                            <p class="text-xs font-bold text-slate-500">{{ $profile->grade_level }} • Junior High</p>

                            <div
                                class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between text-xs font-medium text-slate-600">
                                <span>Profile Active</span>
                                <span
                                    class="text-slate-400 font-bold group-hover:text-blue-500 transition-colors">✓</span>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-3">
                            <div
                                class="rounded-2xl bg-white border border-slate-200 p-6 text-sm text-slate-600 shadow-sm flex items-center gap-3">
                                <span class="text-xl">💡</span>
                                You don’t have any teaching load profiles yet. Add one to quickly reuse subject/grade
                                info while drafting DLLs.
                            </div>
                        </div>
                    @endforelse

                    {{-- Add Profile Button --}}
                    <div @click="showProfileModal = true"
                        class="bg-slate-100/50 rounded-2xl p-5 border border-dashed border-slate-300 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 hover:border-blue-400 hover:shadow-sm transition-all text-center min-h-[140px] group">
                        <span
                            class="w-8 h-8 rounded-full bg-white text-slate-400 group-hover:text-blue-500 flex items-center justify-center font-black shadow-sm mb-2 transition-colors">+</span>
                        <h4 class="font-bold text-slate-600 group-hover:text-blue-700 text-sm transition-colors">
                            Add Subject Profile
                        </h4>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">
                            Assign a new JHS grade level
                        </p>
                    </div>
                </div>
            </div>

            {{-- DLL TABLE --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-visible">
                <div
                    class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📋</span>
                        <h3 class="font-black text-slate-800">All Lesson Logs</h3>
                    </div>

                    {{-- FILTER BADGES --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('teacher.dll.index') }}"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition">All</a>
                        <a href="{{ route('teacher.dll.index', ['status' => 'draft']) }}"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition">Draft</a>
                        <a href="{{ route('teacher.dll.index', ['status' => 'submitted']) }}"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 transition">Submitted</a>
                        <a href="{{ route('teacher.dll.index', ['status' => 'needs_revision']) }}"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">Revision</a>
                        <a href="{{ route('teacher.dll.index', ['status' => 'checked']) }}"
                            class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">Checked</a>
                    </div>
                </div>

                <div class="overflow-visible">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-white border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Teaching Week</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Subject / Grade</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Last Updated</th>
                                <th
                                    class="px-6 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            @forelse ($dailyLessonLogs as $dll)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900">
                                            @if ($dll->week_start_date && $dll->week_end_date)
                                                {{ \Carbon\Carbon::parse($dll->week_start_date)->format('M d') }} -
                                                {{ \Carbon\Carbon::parse($dll->week_end_date)->format('M d, Y') }}
                                            @else
                                                {{ $dll->teaching_dates ?? 'Dates Not Set' }}
                                            @endif
                                        </div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase mt-1">
                                            Created: {{ optional($dll->created_at)->format('M d, Y') ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900 truncate max-w-xs">
                                            {{ $dll->subject }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $dll->grade_quarter }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($dll->status === 'draft')
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">Draft</span>
                                        @elseif($dll->status === 'submitted')
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-200">Submitted</span>
                                        @elseif($dll->status === 'needs_revision')
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-rose-50 text-rose-700 border border-rose-200">Needs
                                                Revision</span>
                                        @elseif($dll->status === 'checked')
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200">Checked</span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600">{{ $dll->status }}</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                                        {{ optional($dll->updated_at)->diffForHumans() ?? 'N/A' }}
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('teacher.dll.show', $dll) }}"
                                                class="inline-flex px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                                View
                                            </a>

                                            @if (in_array($dll->status, ['draft', 'returned', 'needs_revision']))
                                                <a href="{{ route('teacher.dll.edit', $dll) }}"
                                                    class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                                                    title="Edit Blueprint">
                                                    ✏️
                                                </a>
                                            @endif

                                            <a href="{{ route('teacher.dll.week', $dll) }}"
                                                class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                                📅 Matrix
                                            </a>

                                            @if ($dll->status === 'draft')
                                                <form method="POST" action="{{ route('teacher.dll.submit', $dll) }}"
                                                    class="inline-flex m-0"
                                                    onsubmit="return confirm('Ready to submit this lesson plan to your School Head?\n\nYou will not be able to edit your planned activities while it is under review.');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-emerald-600/20">
                                                        🚀 Submit
                                                    </button>
                                                </form>
                                            @endif

                                            @if (in_array($dll->status, ['returned', 'needs_revision']))
                                                <div x-data="{ showReply: false }" class="relative inline-block text-left">
                                                    <button @click="showReply = !showReply" type="button"
                                                        class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white hover:bg-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-amber-500/20">
                                                        🔄 Resubmit
                                                    </button>

                                                    <div x-show="showReply" @click.away="showReply = false" x-transition
                                                        class="absolute bottom-full right-0 mb-2 w-72 bg-white rounded-xl shadow-xl border border-slate-200 p-4 z-50 text-left">
                                                        <form action="{{ route('teacher.dll.resubmit', $dll) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <label
                                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Reply
                                                                to Technical Assistance (Optional)</label>
                                                            <textarea name="teacher_remarks" rows="3" placeholder="e.g., I did not log an assessment on Tuesday because..."
                                                                class="w-full text-xs border-slate-300 rounded-lg focus:ring-amber-500 mb-3"></textarea>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="showReply = false"
                                                                    class="px-3 py-1.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded">Cancel</button>
                                                                <button type="submit"
                                                                    class="px-3 py-1.5 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded hover:bg-amber-600">Send
                                                                    & Resubmit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif

                                            <form method="POST" action="{{ route('teacher.dll.destroy', $dll) }}"
                                                class="inline-flex m-0"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this Lesson Log?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100"
                                                    title="Delete Log">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <span class="text-5xl block mb-3 opacity-50">📄</span>
                                        <h4 class="font-bold text-slate-700 text-base">No Lesson Logs Found</h4>
                                        <p class="text-sm text-slate-500 mt-1">
                                            You haven't drafted any ILAW or MATATAG Weekly Logs yet.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (method_exists($dailyLessonLogs, 'links'))
                    <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                        {{ $dailyLessonLogs->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL: New Subject Profile --}}
        <div x-show="showProfileModal" style="display: none;" class="relative z-50">
            <div x-show="showProfileModal" x-transition.opacity
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showProfileModal" @click.away="showProfileModal = false"
                        class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                        <div
                            class="bg-slate-50/50 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                                    🧬</div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-900">New Subject Profile</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Assign
                                        your teaching load</p>
                                </div>
                            </div>
                            <button @click="showProfileModal = false"
                                class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6">
                            <form action="{{ route('teacher.dll.profile.store') }}" method="POST"
                                class="space-y-5">
                                @csrf
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subject
                                        Name</label>
                                    <select name="subject" required
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-700 cursor-pointer">
                                        <option value="">-- Select Subject --</option>
                                        <option value="Science">Science</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="English">English</option>
                                        <option value="Filipino">Filipino</option>
                                        <option value="Araling Panlipunan">Araling Panlipunan</option>
                                        <option value="MAPEH">MAPEH</option>
                                        <option value="TLE">TLE / TLE-HE</option>
                                        <option value="Values Education">Values Education</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Grade
                                        Level</label>
                                    <select name="grade_level" required
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-700 cursor-pointer">
                                        <option value="">-- Select Grade --</option>
                                        <option value="Grade 7">Grade 7</option>
                                        <option value="Grade 8">Grade 8</option>
                                        <option value="Grade 9">Grade 9</option>
                                        <option value="Grade 10">Grade 10</option>
                                    </select>
                                </div>
                                <div
                                    class="bg-slate-50/50 pt-4 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                                    <button type="button" @click="showProfileModal = false"
                                        class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all">Save
                                        Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL: Draft through Excel --}}
        <div x-show="showExcelModal" style="display: none;" class="relative z-50">
            <div x-show="showExcelModal" x-transition.opacity
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showExcelModal" @click.away="showExcelModal = false"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-100">

                        <div class="bg-slate-50 px-6 py-5 border-b border-slate-100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                                        📊</div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-900">Excel Workspace</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            Offline Drafting</p>
                                    </div>
                                </div>
                                <button @click="showExcelModal = false"
                                    class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex p-1 bg-slate-200/50 rounded-xl">
                                <button @click="excelMode = 'download'"
                                    class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                                    :class="excelMode === 'download' ? 'bg-white text-emerald-700 shadow-sm' :
                                        'text-slate-500 hover:text-slate-700'">1.
                                    Get Template</button>
                                <button @click="excelMode = 'upload'"
                                    class="flex-1 py-2 text-xs font-black uppercase tracking-widest rounded-lg transition-all"
                                    :class="excelMode === 'upload' ? 'bg-white text-blue-700 shadow-sm' :
                                        'text-slate-500 hover:text-slate-700'">2.
                                    Upload Draft</button>
                            </div>
                        </div>

                        <div x-show="excelMode === 'download'" class="p-6">
                            <form action="{{ route('teacher.dll.excel.download') }}" method="GET"
                                class="space-y-5">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Attached
                                        School</label>
                                    <input type="text" name="school_name"
                                        value="{{ auth()->user()->school_name ?? '' }}" required
                                        placeholder="e.g., University of Southern Mindanao - JHS"
                                        class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 text-sm font-medium text-slate-700">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subject</label>
                                        <select name="subject" required
                                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 text-sm font-medium text-slate-700">
                                            <option value="">-- Select --</option>
                                            @foreach ($profiles->pluck('subject')->unique() as $subject)
                                                <option value="{{ $subject }}">{{ $subject }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Grade
                                            Level</label>
                                        <select name="grade_level" required
                                            class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-emerald-500 text-sm font-medium text-slate-700">
                                            <option value="">-- Select --</option>
                                            @foreach ($profiles->pluck('grade_level')->unique() as $grade)
                                                <option value="{{ $grade }}">{{ $grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">DepEd
                                        Framework</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label
                                            class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition"><input
                                                type="radio" name="framework" value="ILAW" required
                                                class="text-emerald-600 focus:ring-emerald-500"><span
                                                class="ml-2 text-sm font-bold text-slate-700">ILAW</span></label>
                                        <label
                                            class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition"><input
                                                type="radio" name="framework" value="MATATAG" required
                                                class="text-emerald-600 focus:ring-emerald-500"><span
                                                class="ml-2 text-sm font-bold text-slate-700">MATATAG</span></label>
                                    </div>
                                </div>
                                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all">Download
                                        Spacious Template ↓</button>
                                </div>
                            </form>
                        </div>

                        <div x-show="excelMode === 'upload'" class="p-6" style="display: none;">
                            <form action="{{ route('teacher.dll.excel.upload') }}" method="POST"
                                enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                <div
                                    class="border-2 border-dashed border-blue-200 bg-blue-50/50 rounded-2xl p-8 text-center hover:bg-blue-50 transition cursor-pointer relative group">
                                    <input type="file" name="excel_file" accept=".xlsx, .xls" required
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">📁</div>
                                    <h4 class="font-black text-blue-900 text-sm">Click or drag your filled Excel file
                                        here</h4>
                                    <p class="text-xs text-blue-600 font-medium mt-1">Must be the exact template
                                        downloaded from step 1.</p>
                                </div>

                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                                    <span class="text-amber-500">⚠️</span>
                                    <p class="text-xs font-medium text-amber-800">
                                        Please ensure you haven't renamed the tabs or deleted any rows from the "READ
                                        ME" structure, otherwise the extraction will fail.
                                    </p>
                                </div>

                                <div class="pt-4 mt-2 border-t border-slate-100 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all">Extract
                                        & Save Draft 🚀</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
