<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="p-2 bg-indigo-100 text-indigo-600 rounded-lg shadow-inner">📤</span>
                    {{ __('Upload Achievement') }}
                </h2>
                <p class="text-sm text-slate-500 font-medium mt-1">Add a new professional development record to your
                    vault.</p>
            </div>

            <a href="{{ route('teacher.portfolio.index') }}"
                class="text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg shadow-sm transition">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-sm font-bold shadow-sm">
                <div class="mb-2 flex items-center gap-2">⚠️ Please fix the following errors:</div>
                <ul class="list-disc pl-8 space-y-1 text-rose-600 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.portfolio.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            @csrf

            <div class="p-6 md:p-8 space-y-8">

                <div>
                    <h3
                        class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">1</span>
                        Achievement Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-0 md:pl-8">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Title of
                                Achievement / Seminar</label>
                            <input type="text" name="title" required value="{{ old('title') }}"
                                placeholder="e.g., Regional Seminar on MATATAG Curriculum"
                                class="w-full border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category</label>
                            <select name="category" required
                                class="w-full border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                                <option value="" disabled selected>Select Category...</option>
                                <option value="Seminars & Training"
                                    {{ old('category') == 'Seminars & Training' ? 'selected' : '' }}>Seminars & Training
                                </option>
                                <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>
                                    Educational Attainment (MA/PhD)</option>
                                <option value="Awards" {{ old('category') == 'Awards' ? 'selected' : '' }}>Awards &
                                    Recognition</option>
                                <option value="Leadership" {{ old('category') == 'Leadership' ? 'selected' : '' }}>
                                    Leadership & Role-Based</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Scope /
                                Level</label>
                            <select name="level" required
                                class="w-full border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                                <option value="" disabled selected>Select Level...</option>
                                <option value="School Level" {{ old('level') == 'School Level' ? 'selected' : '' }}>
                                    School Level</option>
                                <option value="District Level"
                                    {{ old('level') == 'District Level' ? 'selected' : '' }}>District Level</option>
                                <option value="Division Level"
                                    {{ old('level') == 'Division Level' ? 'selected' : '' }}>Division Level</option>
                                <option value="Regional Level"
                                    {{ old('level') == 'Regional Level' ? 'selected' : '' }}>Regional Level</option>
                                <option value="National Level"
                                    {{ old('level') == 'National Level' ? 'selected' : '' }}>National Level</option>
                                <option value="International Level"
                                    {{ old('level') == 'International Level' ? 'selected' : '' }}>International Level
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date
                                Earned / Completed</label>
                            <input type="date" name="date_earned" required
                                value="{{ old('date_earned') ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                                class="w-full md:w-1/2 border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <h3
                        class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">2</span>
                        Means of Verification (MOV)
                    </h3>
                    <div class="pl-0 md:pl-8">
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-indigo-50 hover:border-indigo-300 transition-colors group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-slate-400 group-hover:text-indigo-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-slate-500 group-hover:text-indigo-600"><span
                                            class="font-bold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-400">PDF, JPG, or PNG (MAX. 5MB)</p>
                                </div>
                                <input id="dropzone-file" type="file" name="certificate_file"
                                    accept=".pdf,.jpg,.jpeg,.png" class="hidden" required
                                    onchange="document.getElementById('file-name').textContent = this.files[0].name;" />
                            </label>
                        </div>
                        <p id="file-name" class="mt-2 text-sm font-bold text-indigo-600 text-center"></p>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div>
                    <h3
                        class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">3</span>
                        Reflection & Notes
                    </h3>
                    <div class="pl-0 md:pl-8">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Brief
                            Description</label>
                        <textarea name="description" rows="4" required
                            class="w-full border-slate-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                            placeholder="Briefly describe what this achievement is and how it contributes to your professional growth...">{{ old('description') }}</textarea>
                    </div>
                </div>

            </div>

            <div class="px-6 py-5 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('teacher.portfolio.index') }}"
                    class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save to Vault
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
