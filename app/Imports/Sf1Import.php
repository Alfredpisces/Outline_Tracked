<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\LisStaging;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class Sf1Import implements ToCollection, WithCalculatedFormulas
{
    public $inserted = 0;
    public $updated = 0;
    protected $schoolYear;

    // Persist grade and section across the whole file
    protected $currentGradeLevel = 'Unknown';
    protected $currentSection = 'Unknown';

    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // 1. COLLAPSE MERGED CELLS (The Fix)
            // Remove all nulls or empty strings, then re-index the array so values are side-by-side
            $cleanedRow = array_values(array_filter($row->toArray(), function($value) {
                return $value !== null && trim((string)$value) !== '';
            }));

            // If the row is completely empty after cleaning, skip it
            if (empty($cleanedRow)) {
                continue;
            }

            // 2. AUTO-DETECT GRADE AND SECTION FROM THE HEADER
            // Because we cleaned the array, the target value is always immediately after the label
            foreach ($cleanedRow as $index => $cellValue) {
                $cellString = trim((string)$cellValue);

                if (stripos($cellString, 'Grade Level') !== false && isset($cleanedRow[$index + 1])) {
                    $this->currentGradeLevel = trim((string)$cleanedRow[$index + 1]);
                }

                if (stripos($cellString, 'Section') !== false && isset($cleanedRow[$index + 1])) {
                    $this->currentSection = trim((string)$cleanedRow[$index + 1]);
                }
            }

            // 3. IDENTIFY STUDENT ROWS
            // After cleaning, Index 0 is LRN, 1 is Name, 2 is Sex, 3 is Birthdate
            $lrn = trim((string)$cleanedRow[0]);

            // Check if the first item is a 12-digit LRN
            if (preg_match('/^\d{12}$/', $lrn)) {

                // Safely grab the data using our predictable indexes
                $rawName   = isset($cleanedRow[1]) ? trim((string)$cleanedRow[1]) : '';
                $gender    = isset($cleanedRow[2]) ? trim((string)$cleanedRow[2]) : '';
                $birthDate = isset($cleanedRow[3]) ? trim((string)$cleanedRow[3]) : '';

                // Standardize Gender (M -> Male, F -> Female)
                if (strtoupper($gender) === 'M') $gender = 'Male';
                if (strtoupper($gender) === 'F') $gender = 'Female';

                // 4. SMART CATCHER: PARSE THE NAME
                $parsedName = $this->parseName($rawName);

                // 5. SAVE TO DATABASE
                $record = LisStaging::updateOrCreate(
                    [
                        'lrn'         => $lrn,
                        'school_year' => $this->schoolYear,
                    ],
                    [
                        'last_name'      => $parsedName['last_name'],
                        'first_name'     => $parsedName['first_name'],
                        'middle_name'    => $parsedName['middle_name'],
                        'extension_name' => $parsedName['extension_name'],
                        'gender'         => $gender,
                        'birthdate'      => $birthDate,
                        'grade_level'    => $this->currentGradeLevel,
                        'section'        => $this->currentSection,
                    ]
                );

                // Track analytics for the controller summary
                if ($record->wasRecentlyCreated) {
                    $this->inserted++;
                } else {
                    $this->updated++;
                }
            }
        }
    }

    /**
     * Parse DepEd Name Format: "LASTNAME, FIRSTNAME, EXTENSION, MIDDLENAME"
     * Handles names with and without extensions.
     */
    private function parseName($rawName)
    {
        // Split the string by comma
        $parts = array_map('trim', explode(',', $rawName));

        $lastName = $parts[0] ?? '';
        $firstName = '';
        $middleName = null;
        $extensionName = null;

        $count = count($parts);

        if ($count === 2) {
            // E.g., "DELA CRUZ, JUAN"
            $firstName = $parts[1];
        } elseif ($count === 3) {
            // E.g., "CAGBAY, MHARK BERNARD, MILLIONES"
            $firstName = $parts[1];
            $middleName = $parts[2];
        } elseif ($count >= 4) {
            // E.g., "CALIBO, ERNIE, JR., CAMINO"
            // The extension is typically the 3rd item, and the middle name is the 4th
            $firstName = $parts[1];
            $extensionName = $parts[2];
            $middleName = $parts[3];
        }

        return [
            'last_name'      => $lastName,
            'first_name'     => $firstName,
            'middle_name'    => $middleName,
            'extension_name' => $extensionName,
        ];
    }
}
