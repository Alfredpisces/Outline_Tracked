<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable QR Flyer - TrackEd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Forces the printer to remove margins and hide the print button on paper */
        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 1.6cm;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-white text-slate-900 flex items-center justify-center min-h-screen">

    {{-- Floating Print Button (Hides when printing) --}}
    <button onclick="window.print()"
        class="no-print fixed top-6 right-6 bg-indigo-600 text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg hover:bg-indigo-700 transition">
        Print Flyer
    </button>

    {{-- The Flyer Canvas --}}
    <div class="max-w-2xl w-full text-center space-y-8">

        <div class="space-y-2">
            <h1 class="text-6xl font-black tracking-tight text-slate-900 uppercase">See Something?</h1>
            <h2 class="text-4xl font-extrabold text-indigo-600 uppercase">Say Something.</h2>
        </div>

        <p class="text-lg text-slate-500 font-medium max-w-lg mx-auto">
            Help keep our campus safe. Scan the code below to report an incident, vandalism, or suspicious activity. You
            can choose to remain completely anonymous.
        </p>

        {{-- The Generated QR Code --}}
        <div class="flex justify-center my-12">
            <div class="p-6 border-4 border-slate-900 rounded-3xl bg-white shadow-2xl">
                {!! QrCode::size(300)->generate($publicReportUrl) !!}
            </div>
        </div>

        <div class="space-y-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Or visit manually at:</p>
            <p
                class="text-base font-black text-slate-800 bg-slate-50 py-3 px-6 rounded-xl inline-block border border-slate-200">
                {{ $publicReportUrl }}
            </p>
        </div>

        <div
            class="pt-16 mt-16 border-t-2 border-dashed border-slate-200 flex items-center justify-center gap-2 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 7l10 5 10-5-10-5z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17l10 5 10-5"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12l10 5 10-5"></path>
            </svg>
            <span class="font-bold tracking-widest uppercase text-xs">Powered by TrackEd</span>
        </div>

    </div>

</body>

</html>
