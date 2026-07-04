<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.dll.index') }}"
                class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50 text-slate-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight">Lesson Log Details</h2>
                <p class="text-sm font-bold text-slate-500 mt-1">{{ $dll->dates }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Principal's Feedback (Only visible if Returned) -->
            @if ($dll->status === 'returned')
                <div class="bg-rose-50 border border-rose-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <span
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-rose-200 text-rose-700 font-black">!</span>
                        <h3 class="text-lg font-black text-rose-900">Returned for Revision</h3>
                    </div>
                    <p class="text-sm text-rose-800 font-medium bg-white/60 p-4 rounded-xl border border-rose-100">
                        <span class="block text-[10px] font-black uppercase tracking-widest text-rose-500 mb-1">School
                            Head's Notes:</span>
                        "{{ $dll->principal_feedback ?? 'Please review your procedures and ensure they align with the competencies.' }}"
                    </p>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('teacher.dll.edit', $dll) }}"
                            class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all">
                            Edit & Resubmit
                        </a>
                    </div>
                </div>
            @endif

            <!-- Status Banner -->
            @if ($dll->status === 'approved')
                <div
                    class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-emerald-600 text-xl">✅</span>
                        <span class="font-bold text-emerald-900">Officially Approved</span>
                    </div>
                    <button
                        class="px-4 py-2 bg-white text-emerald-700 border border-emerald-200 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-colors">
                        Print PDF
                    </button>
                </div>
            @endif

            <!-- Document Body -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-8">

                <!-- Header Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <span
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject</span>
                        <span class="font-bold text-slate-800">{{ $dll->subject }}</span>
                    </div>
                    <div>
                        <span
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Grade/Quarter</span>
                        <span class="font-bold text-slate-800">{{ $dll->grade_quarter }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Target
                            Dates</span>
                        <span class="font-bold text-slate-800">{{ $dll->dates }}</span>
                    </div>
                </div>

                <!-- I. Standards -->
                <div>
                    <h3 class="font-black text-slate-800 mb-4 text-lg">I. Curriculum Content & Standards</h3>
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Content
                                Standards</span>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $dll->content_standard }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Competencies</span>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $dll->competencies }}</p>
                        </div>
                    </div>
                </div>

                <!-- II. Procedures -->
                <div>
                    <h3 class="font-black text-slate-800 mb-4 text-lg">II. Teaching Procedures</h3>
                    <div class="space-y-3">
                        <div class="border-l-2 border-blue-500 pl-4 py-1">
                            <span class="block text-[10px] font-black text-blue-500 uppercase tracking-widest">A.
                                Activating Prior Knowledge</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $dll->procedure_a }}</p>
                        </div>
                        <div class="border-l-2 border-blue-500 pl-4 py-1">
                            <span class="block text-[10px] font-black text-blue-500 uppercase tracking-widest">B.
                                Establishing Purpose</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $dll->procedure_b }}</p>
                        </div>
                        <div class="border-l-2 border-indigo-500 pl-4 py-1">
                            <span class="block text-[10px] font-black text-indigo-500 uppercase tracking-widest">C.
                                Developing Understanding</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $dll->procedure_c }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-500 pl-4 py-1">
                            <span class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest">E.
                                Evaluating Learning</span>
                            <p class="text-sm text-slate-700 mt-1">{{ $dll->procedure_e }}</p>
                        </div>
                    </div>
                </div>

                <!-- III. Reflection -->
                @if ($dll->reflection_notes)
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="font-black text-slate-800 mb-4 text-lg">III. Post-Lesson Reflection</h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <span
                                    class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Passed
                                    (80%+)</span>
                                <span class="font-black text-2xl text-blue-900">{{ $dll->passed_count ?? 0 }}</span>
                            </div>
                            <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100">
                                <span
                                    class="block text-[10px] font-black text-amber-600 uppercase tracking-widest">Requires
                                    Remediation</span>
                                <span class="font-black text-2xl text-amber-900">{{ $dll->remedial_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Teaching
                                Strategies & Difficulties</span>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $dll->reflection_notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
