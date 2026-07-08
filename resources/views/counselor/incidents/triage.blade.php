<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('counselor.incidents.queue') }}"
                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-black text-2xl text-slate-800 leading-tight">
                {{ __('Triage Public Tip') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-slate-200 border-t-4 border-t-indigo-500">

            <div class="mb-8 border-b border-slate-100 pb-6 flex justify-between items-end">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Unverified Incident Report</h3>
                    <p class="text-sm text-slate-500 mt-1">Review the details below to determine the appropriate
                        administrative action.</p>
                </div>
                <div class="text-right">
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Submitted On</div>
                    <div class="text-sm font-bold text-slate-700">
                        {{ \Carbon\Carbon::parse($tip->created_at)->format('F d, Y - g:i A') }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- Left Column: Details --}}
                <div class="space-y-6">
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                        <div class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Reporter
                            Information</div>
                        @if ($tip->reporter_name)
                            <div class="text-lg font-black text-slate-900">{{ $tip->reporter_name }}</div>
                            <div class="text-sm font-bold text-slate-600 mt-1">{{ $tip->reporter_contact }}</div>
                        @else
                            <div class="flex items-center gap-2 text-slate-600 font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                                Anonymous Submission
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Incident
                            Location</div>
                        <div
                            class="text-base font-bold text-slate-800 bg-white border border-slate-200 px-4 py-3 rounded-xl shadow-sm">
                            {{ $tip->incident_location }}
                        </div>
                    </div>

                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Factual
                            Narrative</div>
                        <div
                            class="text-sm text-slate-700 leading-relaxed bg-white border border-slate-200 px-5 py-4 rounded-xl shadow-sm">
                            "{{ $tip->narrative }}"
                        </div>
                    </div>

                    @if ($tip->video_link)
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                External Video Link</div>
                            <a href="{{ $tip->video_link }}" target="_blank"
                                class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-3 rounded-xl border border-blue-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Open Video Evidence
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Photographic Evidence --}}
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Photographic
                        Evidence</div>
                    @if ($tip->evidence_path)
                        <div
                            class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 flex items-center justify-center min-h-[300px]">
                            {{-- Image is NOT blurred here because the counselor chose to review it --}}
                            <a href="{{ asset('storage/' . $tip->evidence_path) }}" target="_blank"
                                title="Click to view full size">
                                <img src="{{ asset('storage/' . $tip->evidence_path) }}" alt="Incident Evidence"
                                    class="w-full h-auto object-contain hover:scale-105 transition-transform duration-300">
                            </a>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 text-center mt-2 uppercase tracking-widest">Click
                            image to enlarge</p>
                    @else
                        <div
                            class="rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center p-12 text-slate-400 min-h-[300px]">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-sm font-bold uppercase tracking-widest">No Photo Attached</span>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Action Buttons --}}
            <div
                class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-4 justify-between bg-slate-50 p-6 rounded-2xl">

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- Dismiss Button --}}
                    <form action="{{ route('counselor.tips.process', $tip->id) }}" method="POST"
                        class="w-full sm:w-auto" onsubmit="return confirm('Archive this tip as Spam/Fake?');">
                        @csrf
                        <input type="hidden" name="action" value="dismissed">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white bg-white border border-slate-200 hover:bg-slate-800 rounded-xl transition shadow-sm">
                            Dismiss as Spam
                        </button>
                    </form>

                    {{-- Turn Over Button (For Vendors/Outsiders) --}}
                    <form action="{{ route('counselor.tips.process', $tip->id) }}" method="POST"
                        class="w-full sm:w-auto" onsubmit="return confirm('Turn this over to Police/Barangay?');">
                        @csrf
                        <input type="hidden" name="action" value="turned_over_external">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 text-xs font-black uppercase tracking-widest text-amber-600 hover:text-white bg-amber-50 border border-amber-200 hover:bg-amber-600 rounded-xl transition shadow-sm">
                            Turn Over to Security
                        </button>
                    </form>
                </div>

                {{-- Escalate to Official Record Button --}}
                <a href="{{ route('counselor.incidents.create') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest px-8 py-3.5 rounded-xl shadow-md transition hover:-translate-y-0.5">
                    Link to Student File
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
