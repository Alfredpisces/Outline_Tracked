<?php

namespace App\Exports;

use App\Models\DailyLessonLog;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DllChecklistExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        // Fetch only submitted/checked/needs_revision logs, complete with their daily entries
        $groupedLogs = DailyLessonLog::with(['author', 'entries'])
            ->whereIn('status', ['submitted', 'checked', 'needs_revision'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($log) {
                return $log->author ? $log->author->name : 'Unassigned';
            });

        return view('school_head.exports.dll_checklist', [
            'groupedLogs' => $groupedLogs
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Make the first row (the school header) bold
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
