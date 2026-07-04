<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                    System Settings
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Configure the official school identity, default active school year, and principal information.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 sm:py-10 min-h-[calc(100vh-10rem)]">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3">
                    <div class="text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-900">Success</h4>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-extrabold text-slate-900">School Identity</h3>
                        <p class="text-xs text-slate-500 mt-1">These details will appear on Good Moral certificates and
                            official reports.</p>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                            <div class="shrink-0">
                                <div
                                    class="h-24 w-24 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden">
                                    @if (isset($settings['has_custom_logo']) && $settings['has_custom_logo'] === 'true')
                                        <img src="{{ asset('images/school-logo.png') }}?t={{ time() }}"
                                            alt="School Logo" class="h-full w-full object-contain p-2">
                                    @else
                                        <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 w-full">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Official
                                    School Logo</label>
                                <input type="file" name="school_logo" accept="image/png, image/jpeg"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                                <p class="mt-2 text-xs text-slate-400">PNG or JPG format. Transparent background
                                    recommended. Max 2MB.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Official
                                    School Name</label>
                                <input type="text" name="school_name"
                                    value="{{ old('school_name', $settings['school_name'] ?? '') }}"
                                    class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g. Cawilihan High School">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">DepEd
                                    School ID</label>
                                <input type="text" name="school_id"
                                    value="{{ old('school_id', $settings['school_id'] ?? '') }}"
                                    class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g. 304561">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Default
                                    School Year</label>
                                <input type="text" name="default_school_year"
                                    value="{{ old('default_school_year', $settings['default_school_year'] ?? '') }}"
                                    class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g. 2026-2027">
                            </div>

                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">School
                                    Address / Location</label>
                                <input type="text" name="school_address"
                                    value="{{ old('school_address', $settings['school_address'] ?? '') }}"
                                    class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g. Brgy. Cawilihan, Leon, Iloilo">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-extrabold text-slate-900">Administrative Signatories</h3>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">School
                                Head / Principal Name</label>
                            <input type="text" name="principal_name"
                                value="{{ old('principal_name', $settings['principal_name'] ?? '') }}"
                                class="block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g. Juan Dela Cruz, Principal III">
                            <p class="mt-2 text-xs text-slate-500">This name will be dynamically rendered on signature
                                lines for generated documents.</p>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-md transition-all hover:bg-indigo-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Configuration
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
