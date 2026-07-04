<table style="border-collapse: collapse; width: 100%;">
    <!-- Official Department of Education Header -->
    <tr>
        <td colspan="8" style="text-align: center; font-weight: bold; font-size: 14px;">
            Department of Education
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: center; font-size: 12px;">
            Region XII
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: center; font-size: 12px;">
            Schools Division Office of Cotabato
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: center; font-weight: bold; font-size: 14px;">
            DLL/DLP SUBMISSION CHECKLIST
        </td>
    </tr>
    <tr>
        <td colspan="8"></td> <!-- Blank spacer row -->
    </tr>

    <!-- Dynamic Teacher Blocks -->
    @foreach ($groupedLogs as $teacherName => $logs)
        @php
            // Grab the first log to determine the week dates for the header
            $firstLog = $logs->first();
            $weekLabel = '';
            if ($firstLog && $firstLog->week_start_date && $firstLog->week_end_date) {
                $weekLabel =
                    \Carbon\Carbon::parse($firstLog->week_start_date)->format('F d') .
                    ' - ' .
                    \Carbon\Carbon::parse($firstLog->week_end_date)->format('F d, Y');
            } else {
                $weekLabel = $firstLog->teaching_dates ?? 'Unspecified Date';
            }
        @endphp

        <!-- Teacher Info Row (Mimicking the light green header) -->
        <tr>
            <td colspan="2" style="background-color: #d1fae5; font-weight: bold; border: 2px solid #000000;">
                Name of Teacher:<br>
                {{ strtoupper($teacherName) }}
            </td>
            <td colspan="4"
                style="background-color: #d1fae5; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;">
                Date: {{ $weekLabel }}
            </td>
            <td colspan="2"
                style="background-color: #cffafe; font-weight: bold; border: 2px solid #000000; text-align: center; vertical-align: middle;">
                Remarks
            </td>
        </tr>

        <!-- Monday to Friday Grid Headers -->
        <tr>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 30px;">LEARNING AREAS
            </td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Mon</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Tues</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Wed</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Thurs</td>
            <td style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Fri</td>
            <td colspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000000; width: 40px;">
                Technical Assistance Notes</td>
        </tr>

        <!-- Subjects and Daily Checks -->
        @foreach ($logs as $log)
            <tr>
                <!-- Subject Name & Grade -->
                <td style="font-weight: bold; border: 1px solid #000000;">
                    {{ $log->subject }} ({{ $log->grade_quarter }})
                </td>

                <!-- Day Checkmarks (1 = Monday, 5 = Friday) -->
                @for ($day = 1; $day <= 5; $day++)
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                        @php
                            // Check if the teacher saved an entry for this specific day of the week
                            $hasEntry = $log->entries->contains(function ($entry) use ($day) {
                                return \Carbon\Carbon::parse($entry->entry_date)->dayOfWeekIso === $day;
                            });
                        @endphp

                        @if ($hasEntry)
                            <span style="color: #059669; font-weight: bold;">✔</span>
                        @else
                            <span style="color: #ef4444;"></span>
                        @endif
                    </td>
                @endfor

                <!-- Remarks / Technical Assistance -->
                <td colspan="2"
                    style="border: 1px solid #000000; color: #b91c1c; font-style: italic; vertical-align: top;">
                    @if ($log->status === 'needs_revision')
                        {{ str_replace('🛡️ Technical Assistance from School Head: ', '', $log->reflection_notes) }}
                    @elseif($log->status === 'checked')
                        Checked & Noted
                    @endif
                </td>
            </tr>
        @endforeach

        <!-- Blank Row Separator Between Teachers -->
        <tr>
            <td colspan="8"></td>
        </tr>
    @endforeach
</table>
