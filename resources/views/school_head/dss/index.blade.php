<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-lg font-black text-white bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl shadow-md">
                        📈
                    </div>
                    DSS Analytics & Rankings
                </h2>
                <p class="mt-1 text-sm text-slate-500">Faculty Compliance Leaderboard for RPMS / IPCRF Evaluation</p>
            </div>

            <div class="flex items-center gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Filter:</span>
                    <select name="sy" onchange="this.form.submit()"
                        class="text-sm font-bold text-slate-700 bg-white border-slate-200 rounded-xl focus:ring-indigo-500 shadow-sm py-2 px-4">
                        <option value="2026-2027" {{ $activeSY == '2026-2027' ? 'selected' : '' }}>SY 2026-2027</option>
                        <option value="2025-2026" {{ $activeSY == '2025-2026' ? 'selected' : '' }}>SY 2025-2026</option>
                    </select>
                </form>
                <div class="h-6 w-px bg-slate-300"></div>
                <a href="{{ route('school_head.dashboard') }}"
                    class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 min-h-[calc(100vh-10rem)] bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if ($topPerformers->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
                    <span class="text-5xl block mb-4 opacity-50">📊</span>
                    <h3 class="text-lg font-black text-slate-800">No Analytics Available</h3>
                    <p class="text-sm text-slate-500 mt-1">There is no lesson plan data for this school year yet.</p>
                </div>
            @else
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-4 ml-1">Top Performing
                        Faculty</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($topPerformers as $index => $teacher)
                            <div
                                class="bg-white rounded-2xl p-6 border {{ $index === 0 ? 'border-amber-300 shadow-lg shadow-amber-500/10 relative overflow-hidden' : 'border-slate-200 shadow-sm' }}">

                                @if ($index === 0)
                                    <div
                                        class="absolute top-0 right-0 bg-gradient-to-r from-amber-400 to-amber-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1 rounded-bl-lg shadow-sm">
                                        Rank #1
                                    </div>
                                @endif

                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="w-14 h-14 rounded-full flex items-center justify-center font-black text-xl shadow-inner
                                        {{ $index === 0
                                            ? 'bg-amber-100 text-amber-700 border-2 border-amber-200'
                                            : ($index === 1
                                                ? 'bg-slate-100 text-slate-600 border-2 border-slate-200'
                                                : 'bg-orange-50 text-orange-700 border-2 border-orange-200') }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="font-black text-lg text-slate-900">{{ $teacher->name }}</h4>
                                        <p class="text-xs font-bold text-slate-500">{{ $teacher->total }} Submissions
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Compliance
                                        Score</span>
                                    <span
                                        class="text-2xl font-black {{ $teacher->score >= 80 ? 'text-emerald-600' : 'text-amber-500' }}">
                                        {{ $teacher->score }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-4 ml-1">Complete Faculty
                        Rankings</h3>
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr
                                        class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-black uppercase tracking-widest">
                                        <th class="px-6 py-4">Rank</th>
                                        <th class="px-6 py-4">Faculty Name</th>
                                        <th class="px-6 py-4 text-center">Checked Logs</th>
                                        <th class="px-6 py-4 text-center">Needs Revision</th>
                                        <th class="px-6 py-4 text-center">Pending Review</th>
                                        <th class="px-6 py-4 text-right">DSS Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @php $currentRank = 4; @endphp
                                    @foreach ($otherTeachers as $teacher)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 text-slate-400 font-black">#{{ $currentRank++ }}</td>
                                            <td class="px-6 py-4 font-bold text-slate-800">{{ $teacher->name }}</td>
                                            <td class="px-6 py-4 text-center font-medium text-emerald-600">
                                                {{ $teacher->checked }}</td>
                                            <td class="px-6 py-4 text-center font-medium text-rose-600">
                                                {{ $teacher->needs_revision }}</td>
                                            <td class="px-6 py-4 text-center font-medium text-amber-500">
                                                {{ $teacher->pending }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <span
                                                    class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-xs font-black
                                                    {{ $teacher->score >= 80
                                                        ? 'bg-emerald-100 text-emerald-800'
                                                        : ($teacher->score >= 50
                                                            ? 'bg-amber-100 text-amber-800'
                                                            : 'bg-rose-100 text-rose-800') }}">
                                                    {{ $teacher->score }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
</x-app-layout>
