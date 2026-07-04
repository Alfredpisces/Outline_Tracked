<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-900 tracking-tight">Good Moral Audits</h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden">
                    <div class="p-6 bg-rose-50/50 border-b border-rose-100">
                        <h3 class="font-black text-rose-600 text-lg flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            Blocked Holds
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Has Unresolved
                            Major or Critical Offenses</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($blockedStudents as $student)
                            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $student->first_name }}
                                            {{ $student->last_name }}</h4>
                                        <p class="text-xs font-medium text-slate-500">LRN: {{ $student->lrn }}</p>
                                    </div>
                                    <a href="{{ route('school_head.behavior.cases', ['q' => $student->lrn]) }}"
                                        class="text-[10px] font-black text-rose-500 hover:text-rose-700 uppercase underline">View
                                        Record</a>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-200 text-sm text-slate-600">
                                    <span
                                        class="font-bold text-rose-600 text-xs uppercase tracking-widest block mb-1">Blocking
                                        Offense:</span>
                                    {{ optional($student->incidentReports->first())->offense_type ?? 'Unknown' }}
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 font-bold text-center py-4">No blocked students currently.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-sm border border-emerald-100 overflow-hidden flex flex-col h-full">
                    <div class="p-6 bg-emerald-50/50 border-b border-emerald-100">
                        <h3 class="font-black text-emerald-600 text-lg flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Eligible Students
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Enrolled & Fully
                            Cleared</p>
                    </div>
                    <div class="p-6 space-y-4 flex-grow">
                        @forelse($eligibleStudents as $student)
                            <div
                                class="p-4 rounded-2xl border border-slate-100 bg-slate-50 flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $student->first_name }}
                                        {{ $student->last_name }}</h4>
                                    <p class="text-xs font-medium text-slate-500">LRN: {{ $student->lrn }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-lg">Cleared</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 font-bold text-center py-4">No cleared records available.
                                Ensure LIS Staging is synced.</p>
                        @endforelse
                    </div>

                    @if ($eligibleStudents->hasPages())
                        <div class="p-4 bg-slate-50/50 border-t border-slate-100">
                            {{ $eligibleStudents->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
