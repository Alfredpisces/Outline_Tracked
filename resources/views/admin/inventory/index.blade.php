<x-app-layout>
    <div class="bg-slate-50 py-8 sm:py-10 min-h-[calc(100vh-10rem)] relative">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- PAGE HEADER & ADD BUTTONS --}}
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        Master Inventory
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Manage assets, perform bulk actions, and automate data entry.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <button onclick="document.getElementById('aiScanModal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-50 px-4 py-3 text-xs font-black uppercase tracking-widest text-indigo-600 ring-1 ring-indigo-200 transition hover:bg-indigo-100 hover:-translate-y-0.5 shadow-sm">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        AI Scan Receipt
                    </button>

                    <button onclick="document.getElementById('csvUploadModal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-slate-600 ring-1 ring-slate-200 transition hover:bg-slate-50 hover:-translate-y-0.5 shadow-sm">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Import Excel
                    </button>

                    <a href="{{ route('admin.inventory.create') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-xs font-black uppercase tracking-widest text-white shadow-md transition hover:bg-amber-600 hover:-translate-y-0.5">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Manual
                    </a>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3">
                    <div class="text-emerald-600"><svg class="h-6 w-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-900">Success</h4>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- PROACTIVE ACTION CENTER (Dual Panels) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Panel 1: Low Stock Alerts (Quantity) --}}
                <div class="rounded-3xl border border-rose-200 bg-rose-50 overflow-hidden shadow-sm flex flex-col">
                    <div class="px-6 py-4 bg-rose-100/50 border-b border-rose-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <h3 class="text-sm font-black uppercase tracking-widest text-rose-900">Low Stock Alerts</h3>
                        </div>
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-200 text-xs font-black text-rose-700">{{ $lowStockAlerts->count() ?? 0 }}</span>
                    </div>
                    <div class="p-5 overflow-y-auto max-h-72 custom-scrollbar">
                        @if (isset($lowStockAlerts) && $lowStockAlerts->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($lowStockAlerts as $alert)
                                    <div
                                        class="rounded-2xl bg-white p-4 border border-rose-100 shadow-sm flex flex-col justify-between">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-rose-500">{{ $alert->category }}</span>
                                            <h4 class="font-bold text-slate-900 mt-0.5 line-clamp-1">
                                                {{ $alert->item_name }}</h4>
                                        </div>
                                        <div class="mt-3 flex items-end justify-between">
                                            <div class="text-2xl font-black text-rose-600 leading-none">
                                                {{ $alert->quantity }} <span
                                                    class="text-xs font-semibold text-slate-500">{{ $alert->unit }}</span>
                                            </div>
                                            <div class="text-[10px] font-semibold text-slate-400">Reorder at
                                                {{ $alert->reorder_level }}</div>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                                            <button
                                                class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Flag for Restock
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-rose-400/60 py-8">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs font-bold uppercase tracking-widest text-center">Stock Levels Optimal
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Panel 2: Expiring & Depreciating (Time) --}}
                <div class="rounded-3xl border border-amber-200 bg-amber-50 overflow-hidden shadow-sm flex flex-col">
                    <div class="px-6 py-4 bg-amber-100/50 border-b border-amber-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-sm font-black uppercase tracking-widest text-amber-900">Expiring &
                                End-of-Life</h3>
                        </div>
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-200 text-xs font-black text-amber-700">{{ isset($expiringAlerts) ? $expiringAlerts->count() : '0' }}</span>
                    </div>
                    <div class="p-5 overflow-y-auto max-h-72 custom-scrollbar">
                        @if (isset($expiringAlerts) && $expiringAlerts->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($expiringAlerts as $alert)
                                    <div
                                        class="rounded-2xl bg-white p-4 border border-amber-100 shadow-sm flex flex-col justify-between">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-amber-600">{{ $alert->lifespan_type }}</span>
                                            <h4 class="font-bold text-slate-900 mt-0.5 line-clamp-1">
                                                {{ $alert->item_name }}</h4>
                                        </div>
                                        <div class="mt-3 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs font-bold text-slate-600">Expires: <span
                                                    class="text-amber-600">{{ \Carbon\Carbon::parse($alert->expiry_date)->format('M d, Y') }}</span></span>
                                        </div>
                                        <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                                            <button
                                                class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Flag Replacement
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-amber-400/60 py-8">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs font-bold uppercase tracking-widest text-center">No Expiring Assets
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Master Inventory Table --}}
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Bulk Action Toolbar (Hidden by default) --}}
                <div id="bulkActionBar"
                    class="hidden bg-indigo-50 border-b border-indigo-100 px-6 py-3 flex items-center justify-between transition-all">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-black text-white"
                            id="selectedCount">0</span>
                        <span class="text-xs font-black uppercase tracking-widest text-indigo-900">Assets
                            Selected</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <select
                            class="rounded-xl border-indigo-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                            <option>Reassign Location...</option>
                            <option>Change Condition to...</option>
                            <option>Assign to Personnel...</option>
                            <option>Generate QR Codes</option>
                            <option>Delete Selected</option>
                        </select>
                        <button
                            class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-black uppercase tracking-widest text-white shadow-sm hover:bg-indigo-700 transition hover:-translate-y-0.5">Apply
                            Action</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead
                            class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-black w-10">
                                    <input type="checkbox" id="selectAllCheckbox"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                </th>
                                <th class="px-6 py-4 font-black">Item Details</th>
                                <th class="px-6 py-4 font-black">Type & Qty</th>
                                <th class="px-6 py-4 font-black">Assignment</th>
                                <th class="px-6 py-4 font-black">Condition</th>
                                <th class="px-6 py-4 font-black text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($inventories as $item)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <input type="checkbox"
                                            class="row-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                    </td>
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
                                                    {{ $item->property_number ?? 'No Property No.' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-700">{{ $item->quantity }}
                                            {{ $item->unit }}</div>
                                        <div class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5">
                                            {{ $item->lifespan_type }}</div>
                                        @if ($item->expiry_date)
                                            <div class="text-[9px] font-bold text-amber-500 mt-0.5">EXP:
                                                {{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->assignment_type === 'Facility')
                                            <div class="font-semibold text-indigo-700 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $item->location ?? 'Unspecified Room' }}
                                            </div>
                                        @else
                                            <div class="font-semibold text-emerald-700 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Personal Issue
                                            </div>
                                        @endif
                                        <div class="text-[10px] uppercase tracking-widest text-slate-400 mt-0.5">
                                            {{ $item->assignee ? $item->assignee->first_name . ' ' . $item->assignee->last_name : 'Unassigned' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $color = match ($item->condition) {
                                                'Serviceable' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                                                'Needs Minor Repair' => 'bg-amber-100 text-amber-800 ring-amber-200',
                                                'Needs Major Repair' => 'bg-orange-100 text-orange-800 ring-orange-200',
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
                                        <div
                                            class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" title="Duplicate Asset"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 text-slate-500 hover:bg-sky-50 hover:text-sky-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>

                                            <a href="{{ route('admin.inventory.edit', $item) }}" title="Edit Asset"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('admin.inventory.destroy', $item) }}"
                                                method="POST" onsubmit="return confirm('Delete this asset?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete Asset"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="font-bold">No assets found.</p>
                                        <p class="text-xs mt-1">Click any of the Add buttons above to populate your
                                            inventory.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($inventories->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $inventories->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div id="aiScanModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">
        <div
            class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl transform transition-transform scale-100 relative">
            <button onclick="document.getElementById('aiScanModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900">AI Receipt Scanner</h3>
            <p class="text-sm text-slate-500 mt-1 mb-6">Upload a Delivery Receipt (DR). AI will extract names,
                quantities, and dates automatically.</p>

            <div
                class="border-2 border-dashed border-indigo-200 rounded-2xl bg-indigo-50/50 p-8 text-center cursor-pointer hover:bg-indigo-50 transition">
                <svg class="mx-auto h-8 w-8 text-indigo-400 mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span class="text-sm font-bold text-indigo-600">Click to upload photo</span>
                <p class="text-[10px] text-slate-400 mt-1">JPEG, PNG, or PDF</p>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="document.getElementById('aiScanModal').classList.add('hidden')"
                    class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Cancel</button>
                <button
                    class="rounded-xl bg-indigo-600 px-6 py-2 text-xs font-black uppercase tracking-widest text-white shadow-md hover:bg-indigo-700">Scan
                    Document</button>
            </div>
        </div>
    </div>

    <div id="csvUploadModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm transition-opacity">
        <div
            class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl transform transition-transform scale-100 relative">
            <button onclick="document.getElementById('csvUploadModal').classList.add('hidden')"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900">Bulk Excel Upload</h3>
            <p class="text-sm text-slate-500 mt-1 mb-6">Upload your school's existing RPCPPE. Ensure it matches the
                standard CSV template.</p>

            <div
                class="border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 p-8 text-center cursor-pointer hover:bg-slate-100 transition">
                <svg class="mx-auto h-8 w-8 text-slate-400 mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span class="text-sm font-bold text-slate-600">Browse for .csv file</span>
                <p class="text-[10px] text-slate-400 mt-1">Download Template Here</p>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="document.getElementById('csvUploadModal').classList.add('hidden')"
                    class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Cancel</button>
                <button
                    class="rounded-xl bg-slate-800 px-6 py-2 text-xs font-black uppercase tracking-widest text-white shadow-md hover:bg-slate-900">Upload
                    Data</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAllCheckbox');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkActionBar = document.getElementById('bulkActionBar');
            const selectedCountDisplay = document.getElementById('selectedCount');

            function updateBulkBar() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                selectedCountDisplay.textContent = checkedCount;

                if (checkedCount > 0) {
                    bulkActionBar.classList.remove('hidden');
                } else {
                    bulkActionBar.classList.add('hidden');
                    selectAll.checked = false;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    rowCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkBar();
                });
            }

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkBar);
            });
        });
    </script>
</x-app-layout>
