<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                    My Assigned Inventory
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Review your personal issuances and manage facility assets assigned to your care.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 sm:py-10 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- SECTION 1: Personal Issuances --}}
            <section>
                <div class="mb-4 flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 ring-1 ring-emerald-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Personal Accountability</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Laptops, Tablets, &
                            Direct Issuances</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead
                                class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-black">Item Details</th>
                                    <th class="px-6 py-4 font-black">Quantity</th>
                                    <th class="px-6 py-4 font-black">Condition</th>
                                    <th class="px-6 py-4 font-black text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($personalItems as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if ($item->photo_path)
                                                    <div
                                                        class="h-10 w-10 shrink-0 rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                                                        <img src="{{ Storage::url($item->photo_path) }}"
                                                            alt="{{ $item->item_name }}"
                                                            class="h-full w-full object-cover">
                                                    </div>
                                                @else
                                                    <div
                                                        class="h-10 w-10 shrink-0 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                                                    <div
                                                        class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5">
                                                        Prop #: {{ $item->property_number ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-700">{{ $item->quantity }}
                                                {{ $item->unit }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $color = match ($item->condition) {
                                                    'Serviceable' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                                                    'Needs Minor Repair'
                                                        => 'bg-amber-100 text-amber-800 ring-amber-200',
                                                    'Needs Major Repair'
                                                        => 'bg-orange-100 text-orange-800 ring-orange-200',
                                                    'Unserviceable' => 'bg-rose-100 text-rose-800 ring-rose-200',
                                                    'Missing' => 'bg-slate-100 text-slate-800 ring-slate-200',
                                                    default => 'bg-slate-100 text-slate-800 ring-slate-200',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest ring-1 ring-inset {{ $color }}">
                                                {{ $item->condition }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('teacher.inventory.damage', $item) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-[10px] font-black uppercase tracking-widest text-rose-600 shadow-sm ring-1 ring-rose-200 transition hover:bg-rose-50">
                                                Report Damage
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                            <p class="font-bold">No personal items assigned.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- SECTION 2: Facility & Lab Assets --}}
            <section>
                <div class="mb-4 flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 ring-1 ring-indigo-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Facility Accountability</h3>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Shared Equipment,
                            Consumables & Lab Tools</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead
                                class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-black">Item & Location</th>
                                    <th class="px-6 py-4 font-black">Stock Status</th>
                                    <th class="px-6 py-4 font-black text-right">Transaction Log</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($facilityItems as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                                            <div
                                                class="text-[10px] uppercase tracking-widest text-indigo-500 mt-0.5 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $item->location ?? 'Unspecified' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-end gap-2">
                                                <span
                                                    class="text-lg font-black text-slate-900 leading-none">{{ $item->quantity }}</span>
                                                <span class="text-xs font-semibold text-slate-500">{{ $item->unit }}
                                                    Available</span>
                                            </div>
                                            <div class="text-[10px] uppercase tracking-widest text-slate-400 mt-1">
                                                {{ $item->lifespan_type }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('teacher.inventory.borrow', $item) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-[10px] font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700">
                                                Log Borrow/Usage
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                            <p class="font-bold">No facility assets assigned to your coordination.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
