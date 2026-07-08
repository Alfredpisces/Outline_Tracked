<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit an Anonymous Tip</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen pb-12">

    <div class="bg-slate-900 pt-12 pb-24 px-6 rounded-b-[3rem] shadow-lg">
        <div class="max-w-md mx-auto text-center">
            <h1 class="text-3xl font-black tracking-tight text-white">Incident Intake</h1>
            <p class="text-sm text-slate-400 font-medium mt-2">Submit a secure, direct report to the campus counselor.
                You can choose to remain completely anonymous.</p>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 -mt-16">
        @if (session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-100">
            <form action="{{ route('public.tip.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                {{-- Alpine Logic for Anonymity Toggle --}}
                <div x-data="{ anonymous: true }" class="space-y-4">

                    <label
                        class="flex items-center gap-3 p-4 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer hover:bg-slate-100 transition">
                        <input type="checkbox" name="is_anonymous" value="1" x-model="anonymous"
                            class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-slate-300">
                        <span class="text-sm font-black text-slate-800">I want to remain anonymous</span>
                    </label>

                    {{-- Identity Fields (Hidden by default) --}}
                    <div x-show="!anonymous" x-collapse
                        class="space-y-4 bg-white border border-indigo-100 rounded-xl p-4 shadow-sm ring-1 ring-indigo-50">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Your
                                Name</label>
                            <input type="text" name="reporter_name"
                                class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-500">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Grade
                                & Section / Contact</label>
                            <input type="text" name="reporter_contact"
                                class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-500">
                        </div>
                    </div>

                </div>

                <hr class="border-slate-100">

                {{-- Incident Details --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Where did
                        this happen?</label>
                    <input type="text" name="incident_location" required placeholder="e.g. Canteen, Main Gate, Gym"
                        class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">What
                        happened?</label>
                    <textarea name="narrative" required rows="4" placeholder="Provide as much factual detail as possible..."
                        class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 focus:bg-white focus:border-indigo-500 resize-none"></textarea>
                </div>

                <hr class="border-slate-100">

                {{-- Hybrid Evidence System --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Upload
                            Photo (Optional, Max 5MB)</label>
                        <input type="file" name="evidence_photo" accept="image/jpeg,image/png,image/jpg"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Video
                            Link (Optional)</label>
                        <input type="url" name="video_link" placeholder="Paste Google Drive or YouTube link"
                            class="w-full rounded-xl border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 focus:bg-white focus:border-indigo-500">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 hover:bg-indigo-600 text-white text-sm font-black uppercase tracking-widest px-8 py-4 rounded-xl shadow-lg transition-all hover:-translate-y-0.5 mt-4">
                    Submit Report
                </button>
            </form>
        </div>
    </div>

</body>

</html>
