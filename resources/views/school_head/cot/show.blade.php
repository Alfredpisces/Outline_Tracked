<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between no-print">
            <div>
                <h2 class="text-xl font-black text-slate-800">{{ __('Completed Observation Record') }}</h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Record ID: #{{ $observation->id }}</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()"
                    class="text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg flex items-center gap-2 shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print / Save as PDF
                </button>
                <a href="{{ route('school_head.cot.index') }}"
                    class="text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg shadow-sm">
                    Back to Roster
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8 printable-area">
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden print-clean">

            <div class="bg-slate-800 p-8 text-white flex justify-between items-center header-clean">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Evaluated Teacher</p>
                    <h3 class="text-3xl font-black">{{ $observation->teacher->first_name ?? 'Unknown' }}
                        {{ $observation->teacher->last_name ?? 'Teacher' }}</h3>
                    <p class="text-sm text-slate-300 mt-1">{{ $observation->teacher->deped_rank ?? 'Unassigned Rank' }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Final Rating</p>
                    <p class="text-5xl font-black text-emerald-400">{{ number_format($observation->rating, 2) }}</p>
                </div>
            </div>

            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 bg-slate-50 border-b border-slate-200 text-sm meta-clean">
                <div>
                    <span class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Observation
                        Date</span>
                    <span
                        class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($observation->observation_date)->format('F d, Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Time Started /
                        Ended</span>
                    <span
                        class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($observation->time_started)->format('g:i A') }}
                        - {{ \Carbon\Carbon::parse($observation->time_ended)->format('g:i A') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Subject &
                        Grade</span>
                    <span class="font-bold text-slate-900">{{ $observation->subject_taught }}
                        ({{ $observation->grade_level }})</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Section &
                        Quarter</span>
                    <span class="font-bold text-slate-900">{{ $observation->section }} |
                        Q{{ $observation->quarter }}</span>
                </div>
            </div>

            <div class="p-8">
                <h4 class="text-lg font-black text-slate-800 mb-6 uppercase tracking-wide">Observable Indicators</h4>
                <div class="space-y-4">
                    @foreach ($observation->scores as $num => $indicator)
                        <div
                            class="flex items-start justify-between p-5 rounded-xl border border-slate-200 bg-slate-50 border-clean">
                            <div class="pr-6 flex-1">
                                <span
                                    class="inline-block bg-slate-800 text-white text-xs font-bold px-2.5 py-1 rounded mb-2 text-clean">Indicator
                                    {{ $num }}</span>
                                <p class="text-sm text-slate-800 font-medium leading-relaxed">
                                    {{ $indicator['title'] ?? '' }}</p>
                            </div>
                            <div class="flex-shrink-0 text-center w-24">
                                <span
                                    class="block text-3xl font-black text-emerald-600 score-clean">{{ $indicator['score'] ?? '-' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-200 comment-clean">
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-3">Other Comments /
                    Observations</h4>
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $observation->comments }}</p>
                </div>

                <div class="hidden print:flex justify-between mt-16 pt-8 border-t border-slate-300">
                    <div class="text-center">
                        <div class="w-48 border-b border-black mb-2"></div>
                        <p class="text-xs font-bold uppercase">Observer Signature</p>
                    </div>
                    <div class="text-center">
                        <div class="w-48 border-b border-black mb-2"></div>
                        <p class="text-xs font-bold uppercase">Teacher Signature</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printable-area,
            .printable-area * {
                visibility: visible;
            }

            .printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .print-clean {
                border: none !important;
                box-shadow: none !important;
            }

            .header-clean {
                background-color: white !important;
                color: black !important;
                border-bottom: 2px solid black;
                padding-left: 0;
                padding-right: 0;
            }

            .header-clean p,
            .header-clean h3 {
                color: black !important;
            }

            .meta-clean {
                background-color: white !important;
                padding-left: 0;
                padding-right: 0;
            }

            .border-clean {
                background-color: white !important;
                border: none !important;
                border-bottom: 1px solid #ddd !important;
                padding-left: 0;
                padding-right: 0;
            }

            .text-clean {
                background-color: transparent !important;
                color: black !important;
                border: 1px solid black;
            }

            .score-clean {
                color: black !important;
            }

            .comment-clean {
                background-color: white !important;
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
</x-app-layout>
