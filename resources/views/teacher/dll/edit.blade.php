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
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl shadow-md">
                        ✏️
                    </div>
                    Edit Weekly Blueprint
                </h2>
                <p class="mt-1 text-sm text-slate-500">Update curriculum standards and resources for the week.</p>
            </div>
            <a href="{{ route('teacher.dll.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">Cancel & Go Back</a>
        </div>
    </x-slot>

    <div class="py-8 min-h-[calc(100vh-10rem)] bg-slate-50/50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="{{ route('teacher.dll.update', $dll) }}" method="POST"
                class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-8">
                    <!-- Global Settings -->
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-xl font-black text-slate-800">I. Curriculum Content & Standards</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Weekly Global
                            Settings</p>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Subject
                                Area</label>
                            <input type="text" name="subject" value="{{ $dll->subject }}" required
                                class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Grade &
                                Quarter</label>
                            <input type="text" name="grade_quarter" value="{{ $dll->grade_quarter }}" required
                                class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Week
                                Start (Mon)</label>
                            <input type="date" name="week_start_date"
                                value="{{ optional($dll->week_start_date)->format('Y-m-d') }}" required
                                class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Week End
                                (Fri)</label>
                            <input type="date" name="week_end_date"
                                value="{{ optional($dll->week_end_date)->format('Y-m-d') }}" required
                                class="w-full bg-white border-slate-200 rounded-xl focus:ring-blue-500 text-sm font-medium text-slate-900">
                        </div>
                    </div>

                    <!-- Compiled Standards -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">A.
                                Content & Performance Standards</label>
                            <textarea name="content_standard" rows="3"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm text-slate-700">{{ $dll->content_standard }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">B.
                                Learning Competencies, Objectives & Integration</label>
                            <textarea name="competencies" rows="4"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm text-slate-700">{{ $dll->competencies }}</textarea>
                        </div>
                    </div>

                    <!-- Resources -->
                    <div class="border-t border-slate-100 pt-6 mt-6">
                        <h3 class="text-xl font-black text-slate-800 mb-4">II. Learning Resources</h3>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">References
                                & Materials</label>
                            <textarea name="resources" rows="3"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500 text-sm text-slate-700">{{ $dll->resources }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Controls -->
                <div class="bg-slate-50 border-t border-slate-200 p-6 flex items-center justify-between">
                    <a href="{{ route('teacher.dll.index') }}"
                        class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-sm">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-8 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-slate-900/20">
                        Save Header Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
