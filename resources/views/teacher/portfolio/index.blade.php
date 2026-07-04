<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="p-2 bg-indigo-600 text-white rounded-lg shadow-sm">🏆</span>
                    {{ __('Professional Growth Vault') }}
                </h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Curate your career achievements, seminars, and
                    recognitions.</p>
            </div>

            <div class="mt-4 sm:mt-0">
                <button onclick="window.location.href='{{ route('teacher.portfolio.create') ?? '#' }}'"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Upload Achievement
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 space-y-6">

        <!-- Filters & Summary -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="#"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-800 text-white shadow-sm transition">All
                    Records</a>
                <a href="#"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 transition">Seminars
                    & Training</a>
                <a href="#"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 transition">Education</a>
                <a href="#"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 transition">Awards</a>
            </div>

            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Search your vault..."
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow shadow-sm bg-slate-50">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- The Achievement Grid -->
        @if (isset($achievements) && $achievements->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($achievements as $item)
                    <div
                        class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full relative overflow-hidden">

                        <!-- Category Color Accent -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span
                                    class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $item->category ?? 'Training' }}
                                </span>
                            </div>
                            <span
                                class="text-xs font-bold text-slate-400">{{ \Carbon\Carbon::parse($item->date_earned)->format('M Y') }}</span>
                        </div>

                        <h3 class="text-lg font-black text-slate-800 leading-snug mb-2">{{ $item->title }}</h3>
                        <p class="text-sm text-slate-500 mb-6 flex-grow line-clamp-3">{{ $item->description }}</p>

                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span
                                class="text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                {{ $item->level ?? 'Division Level' }}
                            </span>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <a href="#"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                    title="View Certificate">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="#" method="POST" class="m-0"
                                    onsubmit="return confirm('Delete this record?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div
                class="bg-white border border-slate-200 border-dashed rounded-3xl p-12 text-center max-w-2xl mx-auto mt-8">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl">🏅</span>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Your Vault is Empty</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto">Start building your professional portfolio by uploading
                    your first certificate, award, or proof of training.</p>
                <button onclick="window.location.href='{{ route('teacher.portfolio.create') ?? '#' }}'"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-slate-900 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Achievement
                </button>
            </div>
        @endif

    </div>
</x-app-layout>
