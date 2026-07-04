<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Batch Good Moral Certificates - SY {{ $schoolYear }}</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .batch-container {
            width: 100%;
            max-width: 210mm;
            /* Standard A4 width */
            margin: 0 auto;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 16px;
            border: 2px dashed #cbd5e1;
            margin-top: 40px;
            color: #64748b;
        }

        .empty-state h2 {
            color: #e11d48;
            margin-bottom: 10px;
            font-weight: 900;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }

            .batch-container {
                max-width: none;
            }

            .empty-state {
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="batch-container">
        @forelse($students as $student)
            @include('counselor.good_moral.certificate', ['student' => $student])
        @empty
            <div class="empty-state">
                <h2>⚠️ No Eligible Students Found</h2>
                <p>The system could not find any cleared students for <strong>SY {{ $schoolYear }}</strong> in
                    <strong>{{ $scope === 'all' ? 'All Grades' : 'Grade ' . $gradeLevel }}</strong>.</p>
                <p style="font-size: 12px; margin-top: 15px;"><em>Please verify that the Admin has uploaded the LIS
                        Staging data for this specific school year and grade level.</em></p>
            </div>
        @endforelse
    </div>

    @if ($students->count() > 0)
        <script>
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        </script>
    @endif
</body>

</html>
