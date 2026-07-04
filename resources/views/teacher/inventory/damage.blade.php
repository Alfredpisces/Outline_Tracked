<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.inventory.index') }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50 hover:text-rose-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Report Damage: <span
                        class="text-rose-600">{{ $inventory->item_name }}</span></h2>
                <p class="text-sm text-slate-500">Submit an incident report for broken, malfunctioning, or missing
                    assets.</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="mb-6 rounded-2xl bg-rose-50 p-4 border border-rose-100 flex items-start gap-4 shadow-sm">
                <div class="flex shrink-0 h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-rose-900">Important Note on Accountability</h3>
                    <p class="mt-1 text-xs text-rose-700">Submitting this form logs a permanent audit trail. Please
                        provide accurate details and clear photo evidence to help the Property Custodian determine
                        repair or replacement costs.</p>
                </div>
            </div>

            <form action="{{ route('teacher.inventory.damage.store', $inventory) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="grid grid-cols-1 gap-6">

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">New
                                Condition <span class="text-rose-500">*</span></label>
                            <select name="condition" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-rose-500 focus:ring-rose-500">
                                <option value="">-- Select Damage Level --</option>
                                <option value="Needs Minor Repair">Needs Minor Repair (Quick Fix needed)</option>
                                <option value="Needs Major Repair">Needs Major Repair (Currently Unusable)</option>
                                <option value="Unserviceable">Unserviceable (Completely Destroyed / Condemned)</option>
                                <option value="Missing">Missing / Lost</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Detailed
                                Notes (What happened?) <span class="text-rose-500">*</span></label>
                            <textarea name="notes" rows="4" required
                                placeholder="Explain how the item was damaged or when you noticed it missing..."
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-rose-500 focus:ring-rose-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Upload
                                Photo Evidence (Optional but Recommended)</label>
                            <input type="file" name="photo" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200 transition-all cursor-pointer">
                            <p class="mt-2 text-xs text-slate-400">Clear pictures of the damage speed up the
                                repair/replacement process.</p>
                        </div>

                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-rose-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                        Submit Damage Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
