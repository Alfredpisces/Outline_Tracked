<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-800">{{ __('Classroom Observation Report') }}</h2>
            <a href="{{ route('teacher.cot.index') }}"
                class="text-sm font-bold text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-4 py-2 rounded-lg">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Document Header (Like a DepEd Form) -->
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <div class="bg-slate-800 p-6 text-white flex justify-between items-center">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Observee</p>
                    <h3 class="text-2xl font-black">{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                    <p class="text-sm text-slate-300 mt-1">{{ $teacher->deped_rank ?? 'Rank Pending' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Final Rating</p>
                    <p class="text-4xl font-black text-emerald-400">{{ $observation->rating }}</p>
                </div>
            </div>

            <!-- Metadata Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-slate-50 border-b border-slate-200 text-sm">
                <div>
                    <span class="block text-slate-500 font-bold mb-1">Date of Observation</span>
                    <span
                        class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($observation->created_at)->format('F d, Y') }}</span>
                </div>
                <div>
                    <span class="block text-slate-500 font-bold mb-1">Subject & Grade</span>
                    <span class="font-medium text-slate-900">{{ $observation->subject_taught }}
                        ({{ $observation->grade_level }})</span>
                </div>
                <div>
                    <span class="block text-slate-500 font-bold mb-1">Quarter</span>
                    <span class="font-medium text-slate-900">Quarter {{ $observation->quarter }}</span>
                </div>
                <div>
                    <span class="block text-slate-500 font-bold mb-1">Observer</span>
                    <span class="font-medium text-slate-900">{{ $observation->observer_name }}</span>
                </div>
            </div>

            <!-- Indicator Scores -->
            <div class="p-6">
                <h4 class="text-lg font-black text-slate-800 mb-4">Indicator Breakdown</h4>

                <div class="space-y-3">
                    @foreach ($observation->scores as $num => $indicator)
                        <div
                            class="flex items-start justify-between p-4 rounded-xl border {{ $indicator['score'] >= 6 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-200' }}">
                            <div class="pr-4">
                                <span
                                    class="inline-block bg-slate-800 text-white text-xs font-bold px-2 py-1 rounded mb-2">Indicator
                                    {{ $num }}</span>
                                <p class="text-sm text-slate-700 font-medium">{{ $indicator['title'] }}</p>
                            </div>
                            <div class="flex-shrink-0 text-center">
                                <span
                                    class="block text-2xl font-black {{ $indicator['score'] >= 6 ? 'text-emerald-600' : 'text-slate-700' }}">
                                    {{ $indicator['score'] }}
                                </span>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Score</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Observer Comments -->
            <div class="p-6 bg-amber-50/50 border-t border-amber-100">
                <h4 class="text-sm font-black text-amber-800 uppercase tracking-wider mb-2">Observer's Comments &
                    Feedback</h4>
                <p class="text-sm text-amber-900 leading-relaxed italic">
                    "{{ $observation->comments }}"
                </p>
            </div>
        </div>

    </div>
</x-app-layout>
