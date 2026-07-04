<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.inventory.index') }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50 hover:text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Add New Asset</h2>
                <p class="text-sm text-slate-500">Register a new item, consumable, or facility equipment.</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-amber-600 mb-6 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Basic Identity
                    </h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Item /
                                Equipment Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="item_name" value="{{ old('item_name') }}" required
                                placeholder="e.g. Acer TravelMate P2 Laptop"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Category
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="category" list="categories" value="{{ old('category') }}"
                                required placeholder="e.g. IT Equipment"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                            <datalist id="categories">
                                <option value="IT Equipment">
                                <option value="Office Supplies">
                                <option value="Furniture">
                                <option value="TLE Tools">
                                <option value="Science Apparatus">
                            </datalist>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Property
                                Number (Optional)</label>
                            <input type="text" name="property_number" value="{{ old('property_number') }}"
                                placeholder="e.g. DEPED-2026-001"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-amber-600 mb-6 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Stock & Condition
                    </h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Lifespan
                                Type <span class="text-rose-500">*</span></label>
                            <select name="lifespan_type" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                                <option value="Non-Expendable"
                                    {{ old('lifespan_type') == 'Non-Expendable' ? 'selected' : '' }}>Non-Expendable
                                    (High Value)</option>
                                <option value="Semi-Expendable"
                                    {{ old('lifespan_type') == 'Semi-Expendable' ? 'selected' : '' }}>Semi-Expendable
                                    (1-5 Years)</option>
                                <option value="Consumable" {{ old('lifespan_type') == 'Consumable' ? 'selected' : '' }}>
                                    Consumable (Supplies)</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Quantity
                                <span class="text-rose-500">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="0"
                                required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Unit
                                of Measure <span class="text-rose-500">*</span></label>
                            <input type="text" name="unit" list="units" value="{{ old('unit', 'Pieces') }}"
                                required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                            <datalist id="units">
                                <option value="Pieces">
                                <option value="Reams">
                                <option value="Boxes">
                                <option value="Sets">
                            </datalist>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Current
                                Condition <span class="text-rose-500">*</span></label>
                            <select name="condition" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                                <option value="Serviceable" {{ old('condition') == 'Serviceable' ? 'selected' : '' }}>
                                    Serviceable (Working)</option>
                                <option value="Needs Minor Repair"
                                    {{ old('condition') == 'Needs Minor Repair' ? 'selected' : '' }}>Needs Minor Repair
                                </option>
                                <option value="Needs Major Repair"
                                    {{ old('condition') == 'Needs Major Repair' ? 'selected' : '' }}>Needs Major Repair
                                </option>
                                <option value="Unserviceable"
                                    {{ old('condition') == 'Unserviceable' ? 'selected' : '' }}>Unserviceable
                                    (Condemned)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Low
                                Stock Alert Level (Optional)</label>
                            <input type="number" name="reorder_level" value="{{ old('reorder_level') }}"
                                min="0" placeholder="e.g. Alert me when stock drops to 5"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h3
                        class="text-sm font-black uppercase tracking-widest text-amber-600 mb-6 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Logistics & Assignment
                    </h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Assignment
                                Type <span class="text-rose-500">*</span></label>
                            <select name="assignment_type" required
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                                <option value="Facility" {{ old('assignment_type') == 'Facility' ? 'selected' : '' }}>
                                    Facility / Room Asset</option>
                                <option value="Personal" {{ old('assignment_type') == 'Personal' ? 'selected' : '' }}>
                                    Personal Issue (Teacher)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Room
                                / Location</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                placeholder="e.g. TLE Culinary Lab"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Assigned
                                To (Custodian/Teacher)</label>
                            <select name="assigned_to"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                                <option value="">-- Leave Unassigned --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Funding
                                Source</label>
                            <select name="funding_source"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                                <option value="">-- Select Source --</option>
                                <option value="MOOE" {{ old('funding_source') == 'MOOE' ? 'selected' : '' }}>MOOE
                                </option>
                                <option value="SEF / LGU"
                                    {{ old('funding_source') == 'SEF / LGU' ? 'selected' : '' }}>SEF / LGU</option>
                                <option value="PTA" {{ old('funding_source') == 'PTA' ? 'selected' : '' }}>PTA
                                </option>
                                <option value="Donation" {{ old('funding_source') == 'Donation' ? 'selected' : '' }}>
                                    Donation</option>
                                <option value="National / DepEd Central"
                                    {{ old('funding_source') == 'National / DepEd Central' ? 'selected' : '' }}>
                                    National / DepEd Central</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Date
                                Acquired</label>
                            <input type="date" name="date_acquired" value="{{ old('date_acquired') }}"
                                class="block w-full rounded-2xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Upload
                                Photo / Evidence</label>
                            <input type="file" name="photo" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer">
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-amber-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Save Asset to Registry
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
