<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                <span>📄</span> Official Lesson Plan Viewer
            </h2>
            <a href="{{ route('school_head.dll.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                ← Back to Checklist
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-[calc(100vh-10rem)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-lg overflow-hidden border border-slate-300">

                <div class="p-8 border-b-4 border-slate-900 bg-slate-50">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
                        <div class="md:col-span-1">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Teacher</p>
                            <p class="font-black text-lg text-slate-900">{{ $dll->author->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Subject & Grade
                            </p>
                            <p class="font-black text-lg text-slate-900">{{ $dll->subject }}<br><span
                                    class="text-sm font-medium">{{ $dll->grade_quarter }}</span></p>
                        </div>
                        <div class="md:col-span-1">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Teaching Dates</p>
                            <p class="font-black text-slate-900">
                                @if ($dll->week_start_date && $dll->week_end_date)
                                    {{ \Carbon\Carbon::parse($dll->week_start_date)->format('M d, Y') }}<br>to
                                    {{ \Carbon\Carbon::parse($dll->week_end_date)->format('M d, Y') }}
                                @else
                                    {{ $dll->teaching_dates }}
                                @endif
                            </p>
                        </div>
                        <div class="md:col-span-1">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Current Status</p>
                            <span
                                class="inline-block px-3 py-1 mt-1 font-black text-[10px] uppercase tracking-widest rounded
                                {{ $dll->status === 'checked' ? 'bg-emerald-100 text-emerald-800' : ($dll->status === 'needs_revision' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ str_replace('_', ' ', $dll->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8 border-b border-slate-200 space-y-6">
                    <div>
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs mb-2">I. Content
                            Standards</h3>
                        <div class="bg-slate-50 p-4 rounded border border-slate-200 text-sm whitespace-pre-wrap">
                            {{ $dll->content_standard ?: 'No standards provided.' }}</div>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs mb-2">II. Performance
                            Standards & Competencies</h3>
                        <div class="bg-slate-50 p-4 rounded border border-slate-200 text-sm whitespace-pre-wrap">
                            {{ $dll->competencies ?: 'No competencies provided.' }}</div>
                    </div>
                </div>

                <div class="p-8">
                    <h3
                        class="font-black text-slate-900 uppercase tracking-widest text-xs mb-6 border-b border-slate-200 pb-2">
                        III. Daily Procedures</h3>

                    @if ($dll->entries->isEmpty())
                        <div
                            class="text-center py-10 bg-slate-50 rounded border border-dashed border-slate-300 text-slate-500 text-sm font-bold">
                            No daily entries have been logged for this week yet.
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach ($dll->entries as $entry)
                                <div class="border border-slate-200 rounded-lg overflow-hidden">
                                    <div
                                        class="bg-indigo-900 text-white px-4 py-2 font-black flex justify-between items-center text-sm">
                                        <span>{{ \Carbon\Carbon::parse($entry->entry_date)->format('l, F d, Y') }}</span>
                                    </div>
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm bg-white">
                                        <div>
                                            <p
                                                class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-2 border-b border-slate-100 pb-1">
                                                Intentions / Activities</p>
                                            <p class="whitespace-pre-wrap text-slate-800 leading-relaxed">
                                                {{ $entry->procedure_a ?: 'No activity logged.' }}</p>
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-2 border-b border-slate-100 pb-1">
                                                Formative Assessment</p>
                                            <p class="whitespace-pre-wrap text-slate-800 leading-relaxed">
                                                {{ $entry->procedure_e ?: 'No assessment logged.' }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 border-t border-slate-200 p-4 text-sm">
                                        <p class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-1">
                                            Teacher's Reflection</p>
                                        <p class="whitespace-pre-wrap text-slate-600 italic">
                                            {{ $entry->reflection_notes ?: 'Pending post-lesson reflection.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($dll->reflection_notes && str_contains($dll->reflection_notes, 'Technical Assistance'))
                    <div class="p-8 bg-rose-50 border-t-2 border-rose-200">
                        <h3
                            class="font-black text-rose-800 uppercase tracking-widest text-xs mb-2 flex items-center gap-2">
                            <span>🛡️</span> Technical Assistance Provided
                        </h3>
                        <p class="text-sm text-rose-900 whitespace-pre-wrap">
                            {{ str_replace('🛡️ Technical Assistance from School Head: ', '', $dll->reflection_notes) }}
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
