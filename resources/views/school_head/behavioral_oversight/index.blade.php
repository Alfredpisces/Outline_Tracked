<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-slate-900 tracking-tight">Behavioral Analytics</h2>
            <button
                class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Generate Official PDF
            </button>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 min-h-screen bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flexible Date Range Filter -->
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row items-end gap-4">
                <form method="GET" action="{{ route('school_head.behavior.analytics') }}"
                    class="flex items-end gap-4 w-full">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Start
                            Date</label>
                        <input type="date" name="start_date" value="{{ $from }}"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">End
                            Date</label>
                        <input type="date" name="end_date" value="{{ $to }}"
                            class="w-full bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-violet-500/20 text-sm font-bold text-slate-700">
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-violet-600 text-white hover:bg-violet-700 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                        Apply Filter
                    </button>
                </form>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div
                    class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest relative z-10">Total
                        Incidents</p>
                    <p class="text-3xl font-black text-slate-800 mt-2 relative z-10">{{ $total }}</p>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-rose-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all">
                    <div
                        class="absolute top-0 right-0 p-4 text-rose-500 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest relative z-10">Major
                        Offenses</p>
                    <p class="text-3xl font-black text-rose-600 mt-2 relative z-10">{{ $majorCount }}</p>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-purple-100 bg-purple-50/50 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all">
                    <div
                        class="absolute top-0 right-0 p-4 text-purple-500 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest relative z-10">External
                        Turnovers</p>
                    <p class="text-3xl font-black text-purple-700 mt-2 relative z-10">{{ $criticalCount ?? 0 }}</p>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-amber-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all">
                    <div
                        class="absolute top-0 right-0 p-4 text-amber-500 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest relative z-10">
                        Active/Pending</p>
                    <p class="text-3xl font-black text-amber-600 mt-2 relative z-10">{{ $activeCount }}</p>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-emerald-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all">
                    <div
                        class="absolute top-0 right-0 p-4 text-emerald-500 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest relative z-10">
                        Resolution Rate</p>
                    <p class="text-3xl font-black text-emerald-600 mt-2 relative z-10">{{ $resolvedRate }}%</p>
                </div>
            </div>

            <!-- Visual Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Incident Trend Line Chart -->
                <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <h3
                        class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                            </path>
                        </svg>
                        Incident Frequency Trend
                    </h3>
                    <div class="relative h-72 w-full">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Severity Donut Chart -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <h3
                        class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Severity Breakdown
                    </h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="severityChart"></canvas>
                    </div>

                    <!-- Chart Legend Details -->
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 font-bold text-slate-600"><span
                                    class="w-3 h-3 rounded-full bg-slate-300"></span> Minor</span>
                            <span class="font-black text-slate-800">{{ $minorCount }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 font-bold text-slate-600"><span
                                    class="w-3 h-3 rounded-full bg-rose-500"></span> Major</span>
                            <span class="font-black text-slate-800">{{ $majorCount }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 font-bold text-slate-600"><span
                                    class="w-3 h-3 rounded-full bg-purple-600"></span> Critical</span>
                            <span class="font-black text-slate-800">{{ $criticalCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Chart Configuration Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Setup the Donut Chart (Severity Breakdown)
            const severityCtx = document.getElementById('severityChart').getContext('2d');
            new Chart(severityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Minor Offenses', 'Major Offenses', 'Critical Turnovers'],
                    datasets: [{
                        data: [{{ $minorCount }}, {{ $majorCount }},
                            {{ $criticalCount ?? 0 }}
                        ],
                        backgroundColor: [
                            '#cbd5e1', // slate-300 for Minor
                            '#f43f5e', // rose-500 for Major
                            '#9333ea' // purple-600 for Critical
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false // Hidden because we built a custom HTML legend below it
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: 'Inter'
                            },
                            bodyFont: {
                                size: 14,
                                family: 'Inter',
                                weight: 'bold'
                            },
                            cornerRadius: 8,
                            displayColors: true
                        }
                    }
                }
            });

            // 2. Setup the Trend Line Chart
            // Note: This expects $chartDates and $chartData arrays from the controller.
            const trendCtx = document.getElementById('trendChart').getContext('2d');

            // Generate a nice gradient fill for the line chart
            let gradientFill = trendCtx.createLinearGradient(0, 0, 0, 300);
            gradientFill.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo top
            gradientFill.addColorStop(1, 'rgba(99, 102, 241, 0.0)'); // Transparent bottom

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates ?? []) !!},
                    datasets: [{
                        label: 'Incidents Logged',
                        data: {!! json_encode($chartData ?? []) !!},
                        borderColor: '#6366f1', // Indigo-500
                        backgroundColor: gradientFill,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // Makes the line smooth/curved
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: 'Inter'
                            },
                            bodyFont: {
                                size: 14,
                                family: 'Inter',
                                weight: 'bold'
                            },
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9', // Very light slate grid lines
                                drawBorder: false,
                            },
                            ticks: {
                                precision: 0, // Force whole numbers for incidents
                                font: {
                                    family: 'Inter',
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: {
                                display: false, // Hide vertical grid lines for cleaner look
                                drawBorder: false,
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 11
                                },
                                color: '#64748b',
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
