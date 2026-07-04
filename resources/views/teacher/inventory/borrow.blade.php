<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.inventory.index') }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50 hover:text-indigo-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Log Usage: <span
                        class="text-indigo-600">{{ $inventory->item_name }}</span></h2>
                <p class="text-sm text-slate-500">Record a student borrowing this item, or log the consumption of
                    supplies.</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div
                class="mb-6 flex items-center justify-between rounded-2xl bg-white p-5 border border-slate-200 shadow-sm">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">Current Stock</h3>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ $inventory->quantity }} <span
                            class="text-sm font-semibold text-slate-500">{{ $inventory->unit }} Available</span></p>
                </div>
                <div class="text-right">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-500">Item Type</h3>
                    <p class="mt-1 text-sm font-bold text-indigo-600">{{ $inventory->lifespan_type }}</p>
                </div>
            </div>

            <form action="{{ route('teacher.inventory.borrow.store', $inventory) }}" method="POST" class="space-y-6">
                @csrf

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                        <div class="sm:col-span-2">
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Student /
                                Borrower Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="borrower_name" required placeholder="e.g. Juan Dela Cruz"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Action
                                Type <span class="text-rose-500">*</span></label>
                            <select name="action_type" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Borrowed">Borrow (Must be returned)</option>
                                @if ($inventory->lifespan_type === 'Consumable')
                                    <option value="Consumed">Consume (Permanent deduction)</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Quantity
                                ({{ $inventory->unit }}) <span class="text-rose-500">*</span></label>
                            <input type="number" name="quantity" value="1" min="1"
                                max="{{ $inventory->quantity }}" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Expected
                                Return Date (Optional)</label>
                            <input type="date" name="expected_return_date"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-2 text-xs text-slate-400">Leave blank if the item is just being used for the
                                current period.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Notes</label>
                            <textarea name="notes" rows="2" placeholder="Any specific reason or condition upon release?"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-indigo-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Log Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
