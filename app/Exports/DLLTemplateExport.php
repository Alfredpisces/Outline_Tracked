<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DllTemplateExport implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $framework = $this->data['framework'];
        $isMatatag = $framework === 'MATATAG';

        // 1. The "READ ME" Sign & Instructions
        $rows = [
            ['TRACKED SYSTEM - OFFLINE DLL DRAFTING TEMPLATE'],
            ['⚠️ IMPORTANT INSTRUCTIONS:'],
            ['1. Do NOT rename this sheet or delete any rows.'],
            ['2. Only type your content inside the blank cells next to the labels.'],
            ['3. To add a new line inside a cell, press ALT + ENTER.'],
            [''],
            // 2. Global Settings
            ['SCHOOL NAME', $this->data['school_name'], 'FRAMEWORK', $framework],
            ['SUBJECT', $this->data['subject'], 'GRADE LEVEL', $this->data['grade_level']],
            ['WEEK START (YYYY-MM-DD)', '', 'WEEK END (YYYY-MM-DD)', ''],
            [''],
            // 3. Curriculum Standards
            ['CONTENT STANDARDS', ''],
            ['PERFORMANCE STANDARDS', ''],
            ['LEARNING COMPETENCIES', ''],
            ['REFERENCES & MATERIALS', ''],
            [''],
            // 4. The Daily Matrix Headers
            ['DAY',
             $isMatatag ? 'ACTIVATING PRIOR KNOWLEDGE / PURPOSE' : 'INTENTIONS (Goal)',
             $isMatatag ? 'DEVELOPING UNDERSTANDING' : 'LEARNING EXPERIENCES',
             $isMatatag ? 'EVALUATION / GENERALIZATION' : 'ASSESSING LEARNING',
             'REMARKS & REFLECTIONS'
            ],
        ];

        // 5. Monday to Friday Rows
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($days as $day) {
            $rows[] = [$day, '', '', '', ''];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Styling the "Spacious & Signed" Excel Sheet
        $sheet->getStyle('A1:E22')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:E22')->getAlignment()->setVertical('top');

        return [
            // Title formatting
            1    => ['font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0F172A']]],
            // Instruction formatting
            'A2:A5'  => ['font' => ['color' => ['argb' => 'FFB45309']]],
            // Header highlighting
            7  => ['font' => ['bold' => true]],
            8  => ['font' => ['bold' => true]],
            11 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
            13 => ['font' => ['bold' => true]],
            14 => ['font' => ['bold' => true]],
            16 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD1D5DB']]],
            // Day Column bolding
            'A17:A21' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Labels / Days
            'B' => 45, // Column 1
            'C' => 45, // Column 2
            'D' => 45, // Column 3
            'E' => 40, // Reflections
        ];
    }

    public function title(): string
    {
        return 'TrackEd DLL Draft';
    }
}
