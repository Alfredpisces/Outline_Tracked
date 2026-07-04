<x-app-layout>
    <div class="bg-slate-50 py-8 sm:py-10 min-h-[calc(100vh-10rem)] relative">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- PAGE HEADER & EXPORT BUTTON --}}
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl flex items-center gap-3">
                        <span
                            class="bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">Executive
                            Asset Oversight</span>
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Review school infrastructure health, approve property disposals, and analyze supply utilization.
                    </p>
                </div>
                <div class="flex shrink-0">
                    <button
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-xs font-black uppercase tracking-widest text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50 hover:-translate-y-0.5">
                        <svg class="mr-2 h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export RPCPPE Report
                    </button>
                </div>
            </div>

            {{-- MACRO ANALYTICS (Financial & Health Summary) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex items-center gap-5">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Registered
                            Assets</p>
                        <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalAssets ?? '0' }} <span
                                class="text-sm font-semibold text-slate-500">Items</span></h3>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex items-center gap-5">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div class="w-full">
                        <div class="flex justify-between items-end mb-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Infrastructure
                                Health</p>
                            <span class="text-sm font-black text-emerald-600">{{ $healthPercentage ?? '0' }}%
                                Good</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden flex">
                            <div class="bg-emerald-500 h-2.5 rounded-l-full"
                                style="width: {{ $healthPercentage ?? 0 }}%"></div>
                            <div class="bg-amber-400 h-2.5" style="width: {{ $repairPercentage ?? 0 }}%"></div>
                            <div class="bg-rose-500 h-2.5" style="width: {{ $unserviceablePercentage ?? 0 }}%"></div>
                        </div>
                        <div class="flex gap-3 mt-2 text-[9px] font-bold uppercase text-slate-400">
                            <span class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-amber-400"></div> Repair
                            </span>
                            <span class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-rose-500"></div> Condemned
                            </span>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-3xl border border-rose-200 bg-rose-50 p-6 shadow-sm flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-rose-100 opacity-50">
                        <svg class="h-32 w-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 relative z-10">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-rose-500">Missing / Unaccounted
                        </p>
                        <h3 class="text-3xl font-black text-rose-700 mt-1">{{ $missingCount ?? '0' }} <span
                                class="text-sm font-semibold text-rose-500">Flags</span></h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- CONDEMNATION & DISPOSAL APPROVAL QUEUE (Takes up 2/3 of screen) --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3 ml-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-600 ring-1 ring-violet-200">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Pending Condemnation Approvals</h3>
                    </div>
                    <p class="text-xs text-slate-500 ml-2 mb-4">Items marked "Unserviceable" by the Custodian requiring
                        Principal sign-off for official disposal/write-off.</p>

                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead
                                    class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4 font-black">Asset Details</th>
                                        <th class="px-6 py-4 font-black">Funding / Age</th>
                                        <th class="px-6 py-4 font-black text-right">Executive Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($pendingDisposals ?? [] as $item)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                                                <div class="text-[10px] uppercase tracking-widest text-rose-500 mt-0.5">
                                                    Prop #: {{ $item->property_number ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-slate-700">
                                                    {{ $item->funding_source ?? 'Unknown Funding' }}</div>
                                                <div
                                                    class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5">
                                                    Acquired:
                                                    {{ $item->date_acquired ? $item->date_acquired->format('Y') : 'Unknown' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-2">
                                                    <button
                                                        class="inline-flex items-center justify-center rounded-xl bg-white px-3 py-2 text-[10px] font-black uppercase tracking-widest text-slate-500 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 transition">
                                                        Reject
                                                    </button>
                                                    <button
                                                        class="inline-flex items-center justify-center rounded-xl bg-violet-600 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white shadow-sm hover:bg-violet-700 transition">
                                                        Approve Write-Off
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                                <div
                                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 mb-3">
                                                    <svg class="h-6 w-6 text-slate-300" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <p class="font-bold">No Pending Approvals</p>
                                                <p class="text-xs mt-1">All unserviceable items have been processed.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SUPPLY BURN RATE INSIGHTS (Takes up 1/3 of screen) --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3 ml-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600 ring-1 ring-sky-200">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">High Consumption</h3>
                    </div>
                    <p class="text-xs text-slate-500 ml-2 mb-4">Consumable items that frequently hit zero stock.</p>

                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                        @forelse($highConsumptionItems ?? [] as $item)
                            <div
                                class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-sky-200 transition-colors">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $item->item_name }}</h4>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-sky-600 mt-0.5">
                                        {{ $item->category }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-widest text-rose-700">
                                        Critically Low
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm font-bold text-slate-400">No high-burn data available yet.</p>
                            </div>
                        @endforelse

                        <button
                            class="w-full mt-2 rounded-xl bg-slate-50 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition">
                            View Full Budget Forecast
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
