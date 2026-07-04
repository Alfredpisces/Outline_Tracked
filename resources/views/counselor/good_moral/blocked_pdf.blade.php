<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blocked Students Report - SY {{ $schoolYear }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4 portrait;
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
            color: #0f172a;
        }

        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: 900;
            color: #e11d48;
            margin: 0 0 10px 0;
        }

        .meta {
            font-size: 13px;
            color: #475569;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th {
            text-align: left;
            background: #f1f5f9;
            padding: 10px;
            font-weight: 800;
            border: 1px solid #cbd5e1;
        }

        td {
            padding: 10px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-rose {
            color: #e11d48;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">Action Required: Students Blocked from Good Moral Clearance</h1>
        <div class="meta">
            <strong>Target School Year:</strong> {{ $schoolYear }} <br>
            <strong>Scope Filter:</strong> {{ $scope === 'all' ? 'All Grades (7–12)' : 'Grade ' . $gradeLevel }} <br>
            <strong>Total Blocked:</strong> {{ $blocked->count() }} Students <br>
            <strong>Generated On:</strong> {{ now()->format('F d, Y h:i A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>LRN</th>
                <th>Student Name</th>
                <th>Grade</th>
                <th>Section</th>
                <th class="text-center">Unresolved Cases</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($blocked as $student)
                <tr>
                    <td>{{ $student->lrn }}</td>
                    <td><strong>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}</strong>
                    </td>
                    <td>{{ $student->grade_level }}</td>
                    <td>{{ $student->section ?? '—' }}</td>
                    <td class="text-center text-rose">{{ $student->blocking_offense_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px;">
                        <strong>No students are currently blocked in this filter scope.</strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 11px; color: #64748b; text-align: center;">
        End of Report • TrackEd Administrative System
    </div>
</body>

</html>
