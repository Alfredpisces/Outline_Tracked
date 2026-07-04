<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading COT Rating Sheet...</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
        }

        .loading-screen {
            position: fixed;
            inset: 0;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4f46e5;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* ONE-PAGE STRICT SIZING (8.5x11 US Letter/Short Bond) */
        #cot-form-content {
            width: 8.5in;
            height: 11in;
            padding: 0.4in;
            background: white;
            margin: 0 auto;
            box-sizing: border-box;
            overflow: hidden;
        }
    </style>
</head>

<body>

    @php
        $isMaster = current(request()->query()) === 'master' || (isset($tier) && $tier === 'master');
        $tierName = $isMaster
            ? 'HIGHLY PROFICIENT TEACHERS (Master Teacher I-IV)'
            : 'PROFICIENT TEACHERS (Teacher I-III)';

        $scaleStr = $isMaster ? '4, 5, 6, 7, 8' : '3, 4, 5, 6, 7';
        $noObserved = $isMaster ? '3' : '2';

        $fileName = 'COT_Blank_Rating_Sheet_' . ($isMaster ? 'Highly_Proficient' : 'Proficient') . '.pdf';
    @endphp

    <div class="loading-screen" id="loading-screen">
        <div class="spinner"></div>
        <h2 class="text-xl font-black text-slate-800">Generating 1-Page PDF...</h2>
        <p class="text-sm text-slate-500 mt-2">Your 8.5x11" (Short) rating sheet will download automatically.</p>
    </div>

    <div id="cot-form-content">

        <!-- Official DepEd Header -->
        <div class="text-center mb-4">
            <h1 class="text-lg font-black uppercase tracking-wider mb-1">Rating Sheet</h1>
            <h2 class="text-sm font-bold uppercase mb-1">Classroom Observation Tool (COT)</h2>
            <h3 class="text-xs font-bold uppercase">RPMS-PPST For {{ $tierName }}</h3>
        </div>

        <!-- Metadata Section -->
        <div class="border-2 border-black p-3 mb-4 text-[11px] font-bold uppercase grid grid-cols-2 gap-x-6 gap-y-2">
            <div class="flex items-end border-b border-black pb-0.5">
                <span class="whitespace-nowrap mr-2">Observer:</span>
                <span class="flex-1"></span>
            </div>
            <div class="flex items-end border-b border-black pb-0.5">
                <span class="whitespace-nowrap mr-2">Date:</span>
                <span class="flex-1"></span>
            </div>
            <div class="flex items-end border-b border-black pb-0.5">
                <span class="whitespace-nowrap mr-2">Teacher Observed:</span>
                <span class="flex-1"></span>
            </div>
            <div class="flex items-end border-b border-black pb-0.5">
                <span class="whitespace-nowrap mr-2">Subject & Grade Level Taught:</span>
                <span class="flex-1"></span>
            </div>
            <div class="flex items-end border-b border-black pb-0.5">
                <span class="whitespace-nowrap mr-2">Observation Period:</span>
                <span class="flex-1"></span>
            </div>
            <div class="flex gap-4">
                <div class="flex items-end border-b border-black pb-0.5 flex-1">
                    <span class="whitespace-nowrap mr-2">Time Started:</span>
                    <span class="flex-1"></span>
                </div>
                <div class="flex items-end border-b border-black pb-0.5 flex-1">
                    <span class="whitespace-nowrap mr-2">Time Ended:</span>
                    <span class="flex-1"></span>
                </div>
            </div>
        </div>

        <!-- Directions & Legend -->
        <div class="mb-4 text-[11px] flex justify-between items-start gap-4">
            <div class="flex-1">
                <p class="font-bold mb-0.5 uppercase">Directions for the Observer:</p>
                <p class="mb-1 text-justify">
                    1. Rate each indicator according to how well the teacher performed during the classroom observation.
                    <strong>Write the exact numerical rating</strong> in the Rating box.
                    <br>2. Each indicator is assessed on an individual basis, regardless of its relationship to other
                    indicators.
                </p>
            </div>
            <div class="w-48 border-2 border-black p-2 bg-gray-50 text-center flex flex-col justify-center">
                <p class="font-black text-[10px] uppercase">Valid Rating Scale</p>
                <p class="font-bold text-sm">{{ $scaleStr }}</p>
                <div class="w-full border-t border-gray-300 my-1"></div>
                <p class="font-bold text-[10px] uppercase">Not Observed: {{ $noObserved }}</p>
            </div>
        </div>

        <!-- The Rating Rubric Table -->
        <table class="w-full border-collapse border-2 border-black mb-4 text-[11px]">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-black p-2 text-center w-8 font-bold uppercase">#</th>
                    <th class="border border-black p-2 text-left font-bold uppercase">Observable Indicators</th>
                    <th class="border border-black p-2 text-center w-24 font-bold uppercase">Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($indicators as $num => $indicator)
                    <tr>
                        <td class="border border-black p-3 text-center font-bold bg-gray-50">{{ $num }}</td>
                        <td class="border border-black p-3 leading-tight pr-4">{{ $indicator }}</td>
                        <td class="border border-black p-3 text-center font-black text-lg"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Comments Section -->
        <div class="mb-6">
            <p class="font-bold uppercase text-[11px] mb-1">Other Comments / Observations:</p>
            <div class="border-2 border-black h-20 w-full"></div>
        </div>

        <!-- Official Signatures -->
        <div class="flex justify-between mt-8 px-8">
            <div class="text-center w-64">
                <div class="border-b-2 border-black mb-1 h-6"></div>
                <p class="text-[10px] font-bold uppercase">Signature over Printed Name of the Observer</p>
            </div>
            <div class="text-center w-64">
                <div class="border-b-2 border-black mb-1 h-6"></div>
                <p class="text-[10px] font-bold uppercase">Signature over Printed Name of the Teacher</p>
            </div>
        </div>
    </div>

    <!-- Script to Auto-Download the PDF -->
    <script>
        window.onload = function() {
            const element = document.getElementById('cot-form-content');

            const opt = {
                margin: 0,
                filename: '{{ $fileName }}',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                document.getElementById('loading-screen').innerHTML =
                    '<h2 class="text-xl font-black text-emerald-600">Download Complete!</h2>' +
                    '<p class="text-sm text-slate-500 mt-2">You can close this tab.</p>';

                setTimeout(() => {
                    window.close();
                }, 1500);
            });
        };
    </script>
</body>

</html>
