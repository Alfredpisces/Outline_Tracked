<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('school_head.behavior.cases') }}"
                class="p-2 bg-white rounded-full shadow-sm border border-slate-200 hover:bg-slate-50 text-slate-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h2 class="font-black text-2xl text-slate-900 tracking-tight">Case Details #{{ $incident->id }}</h2>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div
                    class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 font-bold text-sm shadow-sm flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-6 border-b border-slate-50 gap-4">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">{{ optional($incident->student)->first_name }}
                            {{ optional($incident->student)->last_name }}</h3>
                        <p class="text-sm font-bold text-slate-500 mt-1">LRN: {{ optional($incident->student)->lrn }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $incident->severity === 'critical'
                                ? 'bg-purple-100 text-purple-700 border border-purple-200'
                                : ($incident->severity === 'major'
                                    ? 'bg-rose-50 text-rose-600 border border-rose-100'
                                    : 'bg-slate-100 text-slate-600') }}">
                            {{ $incident->severity }} Offense
                        </span>
                        <span
                            class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $incident->status === 'resolved'
                                ? 'bg-emerald-50 text-emerald-600 border border-emerald-100'
                                : ($incident->status === 'turned_over'
                                    ? 'bg-slate-800 text-white'
                                    : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                            {{ str_replace('_', ' ', $incident->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Offense Type</p>
                        <p class="font-bold text-slate-800 mt-1">{{ $incident->offense_type }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Incident Date</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Narrative</p>
                        <p
                            class="text-sm text-slate-700 mt-2 bg-slate-50 p-5 rounded-2xl border border-slate-100 leading-relaxed">
                            "{{ $incident->narrative }}"</p>
                    </div>

                    @if ($incident->counselor_notes)
                        <div class="md:col-span-2">
                            <p
                                class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Counselor's Notes / Intervention / Handoff Details
                            </p>
                            <p
                                class="text-sm text-indigo-900 mt-2 bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100/50 leading-relaxed">
                                {{ $incident->counselor_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($canIntervene && $incident->status !== 'resolved' && $incident->status !== 'overridden')
                <div
                    class="bg-slate-900 p-8 rounded-3xl shadow-xl relative overflow-hidden border border-slate-800 mt-8">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-violet-600/10 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2">
                    </div>

                    <h3 class="text-lg font-black text-white mb-2 flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-violet-500/20 text-violet-400 border border-violet-500/30">
                            ⚡</div>
                        Executive Intervention
                    </h3>
                    <p class="text-sm text-slate-400 mb-6">As the School Head, record your final executive decision or
                        acknowledge the external handover.</p>

                    <form method="POST" action="{{ route('school_head.behavior.resolve', $incident->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Principal's
                                    Action / Final Resolution</label>
                                <textarea name="principal_action" rows="3"
                                    class="w-full bg-slate-900/50 border border-slate-700 text-slate-200 rounded-xl p-4 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all placeholder-slate-600"
                                    placeholder="State the final executive decision, parental agreements, or acknowledge law enforcement turnover...">{{ $incident->principal_action }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-end gap-3 pt-6 border-t border-slate-800">
                            <input type="hidden" name="status" value="{{ $incident->status }}">

                            <button type="submit"
                                formaction="{{ route('school_head.behavior.update', $incident->id) }}"
                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-black uppercase tracking-widest transition-all focus:ring-2 focus:ring-slate-500 focus:outline-none">
                                Save Notes (Keep Open)
                            </button>

                            @if ($incident->status === 'turned_over')
                                <button type="submit"
                                    class="px-6 py-2.5 bg-slate-100 hover:bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest transition-all focus:ring-2 focus:ring-slate-300 focus:outline-none">
                                    Acknowledge External Handover
                                </button>
                            @else
                                <button type="submit"
                                    class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-violet-600/20 focus:ring-2 focus:ring-violet-500 focus:outline-none">
                                    Resolve & Close Case
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            <!-- THE OVERRIDE FAILSAFE -->
            @if ($incident->severity === 'critical' && in_array($incident->status, ['turned_over', 'resolved']))
                <div
                    class="bg-rose-50 border border-rose-200 p-8 rounded-3xl shadow-sm mt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-lg font-black text-rose-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Good Moral Block Applied
                        </h3>
                        <p class="text-sm text-rose-700 mt-1">This critical case permanently blocks the student from
                            receiving a Good Moral certificate. If external charges were dropped or the student was
                            cleared legally, you may override this lock.</p>
                    </div>
                    <form method="POST" action="{{ route('school_head.behavior.override', $incident->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to override the Good Moral block for this critical incident?');"
                            class="px-5 py-3 bg-white border border-rose-300 hover:bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm transition-all whitespace-nowrap">
                            Override Block
                        </button>
                    </form>
                </div>
            @endif

            @if ($incident->principal_action && in_array($incident->status, ['resolved', 'turned_over', 'overridden']))
                <div class="bg-slate-100 border border-slate-200 p-8 rounded-3xl shadow-sm mt-8">
                    <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                        <span>📝</span> Principal's Final Executive Note
                    </h3>
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Official Action Taken
                        </p>
                        <p
                            class="text-sm text-slate-800 bg-white p-5 rounded-2xl border border-slate-200 leading-relaxed">
                            {{ $incident->principal_action }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
