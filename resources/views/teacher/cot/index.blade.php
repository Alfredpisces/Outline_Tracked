<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-800">{{ __('My Classroom Observations') }}</h2>
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        @if (session('status'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div
            class="bg-indigo-600 rounded-2xl p-6 shadow-sm text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider mb-1">Evaluation Profile</p>
                <h3 class="text-2xl font-black">{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                <div class="flex items-center gap-3 mt-2 text-sm font-medium">
                    <span class="bg-indigo-500/50 px-3 py-1 rounded-full border border-indigo-400/50">
                        {{ $teacher->deped_rank ?? 'Rank Pending' }}
                    </span>
                    <span class="bg-indigo-500/50 px-3 py-1 rounded-full border border-indigo-400/50">
                        {{ $teacher->department ?? 'General' }} Dept.
                    </span>
                </div>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/20 text-center md:text-right">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Required COT Tier</p>
                <p class="text-lg font-black">
                    @if (str_contains($teacher->deped_rank, 'Master'))
                        Highly Proficient (Indicators 1-9)
                    @else
                        Proficient (Indicators 1-9)
                    @endif
                </p>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Request Pre-Observation Schedule</h3>
            <form action="{{ route('teacher.cot.request') }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Proposed
                        Date</label>
                    <input type="date" name="observation_date" required
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Time
                        Started</label>
                    <input type="time" name="time_started" required
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Time
                        Ended</label>
                    <input type="time" name="time_ended" required
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Quarter</label>
                    <select name="quarter" required
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1">Q1</option>
                        <option value="2">Q2</option>
                        <option value="3">Q3</option>
                        <option value="4">Q4</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Subject</label>
                    <input type="text" name="subject_taught" required placeholder="e.g. Math 9"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Grade</label>
                    <input type="text" name="grade_level" required placeholder="e.g. Grade 9"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Section</label>
                    <input type="text" name="section" required placeholder="e.g. Rizal"
                        class="w-full border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

        @if ($activeRequests->isNotEmpty())
            <div class="bg-white shadow-sm border border-amber-200 rounded-2xl overflow-hidden">
                <div class="p-4 bg-amber-50 border-b border-amber-200 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-amber-900 uppercase tracking-wider">Upcoming & Pending
                        Observations</h3>
                </div>
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Class Info</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Assigned Observer</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($activeRequests as $req)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $req->observation_date->format('M d, Y') }} <br>
                                    <span
                                        class="text-xs text-slate-500 font-normal">{{ \Carbon\Carbon::parse($req->time_started)->format('g:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <span class="font-bold text-slate-800">{{ $req->subject_taught }}</span><br>
                                    {{ $req->grade_level }} - {{ $req->section }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $req->observer ? $req->observer->first_name . ' ' . $req->observer->last_name : 'Pending Assignment' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($req->status === 'pending')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-700">Pending
                                            Approval</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-700">Scheduled</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Completed Observation History</h3>
            </div>

            @if ($completedObservations->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-sm text-slate-500 max-w-sm mx-auto">Your official ratings will appear here once an
                        observer completes their evaluation.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Observer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Subject</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Final Rating</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($completedObservations as $obs)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $obs->observation_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $obs->observer->first_name ?? 'Unknown' }}
                                    {{ $obs->observer->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $obs->subject_taught }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-black {{ $obs->rating >= 6 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $obs->rating }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 font-bold">View
                                        Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>
