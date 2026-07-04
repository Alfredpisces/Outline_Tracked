<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-900 tracking-tight">Master Case Logs</h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                <form method="GET" action="{{ route('school_head.behavior.cases') }}"
                    class="flex flex-wrap items-end gap-4">

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Search
                            Student / Offense</label>
                        <input type="text" name="q" value="{{ $q ?? '' }}"
                            placeholder="LRN, Name, or keywords..."
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">From</label>
                        <input type="date" name="start_date" value="{{ $from ?? '' }}"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">To</label>
                        <input type="date" name="end_date" value="{{ $to ?? '' }}"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Severity</label>
                        <select name="severity"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                            <option value="">All Severities</option>
                            <option value="critical" {{ ($severity ?? '') === 'critical' ? 'selected' : '' }}>Critical /
                                Legal Only</option>
                            <option value="major" {{ ($severity ?? '') === 'major' ? 'selected' : '' }}>Major Only
                            </option>
                            <option value="minor" {{ ($severity ?? '') === 'minor' ? 'selected' : '' }}>Minor Only
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="status"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="in_intervention"
                                {{ ($status ?? '') === 'in_intervention' ? 'selected' : '' }}>In Intervention</option>
                            <option value="escalated" {{ ($status ?? '') === 'escalated' ? 'selected' : '' }}>Escalated
                                (Action Required)</option>
                            <option value="turned_over" {{ ($status ?? '') === 'turned_over' ? 'selected' : '' }}>
                                Turned Over (External)</option>
                            <option value="resolved" {{ ($status ?? '') === 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        class="px-6 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                        Filter
                    </button>

                    @if (request()->hasAny(['q', 'start_date', 'end_date', 'severity', 'status']))
                        <a href="{{ route('school_head.behavior.cases') }}"
                            class="px-4 py-2.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Date / Case ID</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Student</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Offense</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($incidents as $incident)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900">
                                            {{ \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase mt-1">ID:
                                            {{ $incident->id }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900">
                                            {{ optional($incident->student)->first_name }}
                                            {{ optional($incident->student)->last_name }}</div>
                                        <div class="text-xs text-slate-500 mt-1">LRN:
                                            {{ optional($incident->student)->lrn }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-medium text-slate-700 max-w-xs truncate"
                                            title="{{ $incident->offense_type }}">{{ $incident->offense_type }}</div>
                                        <span
                                            class="px-2 py-0.5 inline-block mt-1 rounded text-[9px] font-black uppercase tracking-widest
                                            {{ $incident->severity === 'critical' ? 'bg-purple-100 text-purple-700' : ($incident->severity === 'major' ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $incident->severity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                            {{ $incident->status === 'resolved'
                                                ? 'bg-emerald-50 text-emerald-600'
                                                : ($incident->status === 'turned_over'
                                                    ? 'bg-slate-800 text-white'
                                                    : ($incident->status === 'escalated'
                                                        ? 'bg-rose-50 text-rose-600 border border-rose-100'
                                                        : 'bg-amber-50 text-amber-600')) }}">
                                            {{ str_replace('_', ' ', $incident->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('school_head.behavior.show', $incident->id) }}"
                                            class="inline-flex px-4 py-2 {{ in_array($incident->severity, ['major', 'critical']) || $incident->status === 'escalated' ? 'bg-slate-900 text-white hover:bg-slate-800 shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }} rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                            {{ in_array($incident->severity, ['major', 'critical']) || $incident->status === 'escalated' ? 'Intervene' : 'View Log' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-2xl mb-4">
                                            📭</div>
                                        <p class="font-bold text-slate-400">No cases found matching your criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($incidents->hasPages())
                    <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                        {{ $incidents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
