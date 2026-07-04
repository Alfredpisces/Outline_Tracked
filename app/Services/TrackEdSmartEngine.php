<?php

namespace App\Services;

use App\Models\DailyLessonLog;
use Illuminate\Support\Collection;

/**
 * TrackEdSmartEngine
 *
 * Rule-based, fully self-contained analysis engine for DepEd Daily Lesson Logs (DLL).
 * Now includes Context-Awareness (Plagiarism detection) and Instructional Alignment.
 */
class TrackEdSmartEngine
{
    /** Weighting profile for the overall compliance score (should sum to 100). */
    protected array $weights = [
        'completeness'     => 30,
        'assessment'       => 20,
        'reflection'       => 15,
        'cognitive_demand' => 20,
        'originality'      => 15, // NEW: Weight for not copy-pasting
    ];

    /** Revised Bloom's / Anderson taxonomy verbs, grouped by cognitive level. */
    protected array $taxonomy = [
        'remembering'   => ['define', 'list', 'recall', 'name', 'state', 'identify', 'label', 'memorize'],
        'understanding' => ['describe', 'explain', 'discuss', 'summarize', 'classify', 'interpret', 'paraphrase'],
        'applying'      => ['apply', 'demonstrate', 'illustrate', 'solve', 'use', 'compute', 'implement'],
        'analyzing'     => ['analyze', 'compare', 'contrast', 'differentiate', 'examine', 'organize', 'distinguish'],
        'evaluating'    => ['evaluate', 'judge', 'critique', 'justify', 'argue', 'assess', 'defend', 'appraise'],
        'creating'      => ['create', 'design', 'formulate', 'construct', 'compose', 'devise', 'plan', 'generate', 'produce'],
    ];

    protected array $lotsLevels = ['remembering', 'understanding'];
    protected array $motsLevels = ['applying', 'analyzing'];
    protected array $hotsLevels = ['evaluating', 'creating'];

    public function analyzeLessonPlan(DailyLessonLog $dll): array
    {
        $dll->load(['entries', 'author']);
        $entries   = $dll->entries;
        $totalDays = $entries->count();

        if ($totalDays === 0) {
            return $this->blankMatrixResponse();
        }

        $cognitive    = $this->scanCognitiveDemand($dll);
        $completeness = $this->scanCompleteness($entries);
        $history      = $this->checkSystemHistory($dll, $entries); // NEW: Contextual Check

        $perDay       = $this->buildPerDayBreakdown($entries);
        $score        = $this->computeComplianceScore($completeness, $cognitive, $history, $totalDays);

        return [
            'brief'                 => $this->buildBrief($totalDays, $cognitive, $completeness, $history, $score),
            'suggested_feedback'    => $this->buildFeedback($completeness, $cognitive, $history, $totalDays),
            'compliance_score'      => $score,
            'rating'                => $this->scoreToRating($score),
            'cognitive_profile'     => $cognitive,
            'completeness'          => $completeness,
            'strengths'             => $this->identifyStrengths($completeness, $cognitive, $history),
            'areas_for_improvement' => $this->identifyImprovements($completeness, $cognitive, $history),
            'per_day'               => $perDay,
        ];
    }

    protected function blankMatrixResponse(): array
    {
        return [
            'brief'                 => "🚨 Critical: Matrix is completely blank. No instructional days recorded.",
            'suggested_feedback'    => "Please complete your daily procedures before routing for supervision.",
            'compliance_score'      => 0,
            'rating'                => 'Not Ratable',
            'cognitive_profile'     => null,
            'completeness'          => null,
            'strengths'             => [],
            'areas_for_improvement' => ['No entries to evaluate.'],
            'per_day'               => [],
        ];
    }

    protected function scanCognitiveDemand(DailyLessonLog $dll): array
    {
        $text = strtolower(trim(
            ($dll->competencies ?? '') . ' ' .
            ($dll->content_standard ?? '') . ' ' .
            ($dll->performance_standard ?? '')
        ));

        $hits = [];
        foreach ($this->taxonomy as $level => $verbs) {
            $hits[$level] = array_values(array_filter($verbs, fn ($verb) => str_contains($text, $verb)));
        }

        $lotsCount = array_sum(array_map(fn ($l) => count($hits[$l]), $this->lotsLevels));
        $motsCount = array_sum(array_map(fn ($l) => count($hits[$l]), $this->motsLevels));
        $hotsCount = array_sum(array_map(fn ($l) => count($hits[$l]), $this->hotsLevels));

        $verdict = match (true) {
            $hotsCount > 0 && $hotsCount >= $lotsCount => 'hots',
            $motsCount > 0 && $motsCount >= $lotsCount => 'mots',
            $lotsCount > 0                              => 'lots',
            default                                     => 'undetermined',
        };

        return [
            'verdict'    => $verdict,
            'hits'       => $hits,
            'lots_count' => $lotsCount,
            'mots_count' => $motsCount,
            'hots_count' => $hotsCount,
        ];
    }

    protected function scanCompleteness(Collection $entries): array
    {
        $missingAssessments = 0;
        $missingReflections = 0;
        $missingActivities  = 0;

        foreach ($entries as $entry) {
            if ($this->isBlank($entry->procedure_e ?? null)) $missingAssessments++;
            if ($this->isBlank($entry->reflection_notes ?? null)) $missingReflections++;
            if ($this->isBlank($entry->procedure_a ?? null)) $missingActivities++;
        }

        return [
            'missing_assessments' => $missingAssessments,
            'missing_reflections' => $missingReflections,
            'missing_activities'  => $missingActivities,
        ];
    }

    /** NEW: Checks the database for copy-pasting from last week */
    protected function checkSystemHistory(DailyLessonLog $dll, Collection $entries): array
    {
        $currentActivitiesText = "";
        foreach ($entries as $entry) {
            $currentActivitiesText .= strtolower($entry->procedure_a . " ");
        }

        $lastLog = DailyLessonLog::with('entries')
            ->where('author_id', $dll->author_id)
            ->where('id', '!=', $dll->id)
            ->latest('created_at')
            ->first();

        $isCopyPasted = false;

        if ($lastLog && !empty(trim($currentActivitiesText))) {
            $lastActivitiesText = "";
            foreach ($lastLog->entries as $entry) {
                $lastActivitiesText .= strtolower($entry->procedure_a . " ");
            }

            if (!empty(trim($lastActivitiesText))) {
                similar_text($currentActivitiesText, $lastActivitiesText, $percentSimilarity);
                if ($percentSimilarity > 85) {
                    $isCopyPasted = true;
                }
            }
        }

        return [
            'is_copy_pasted' => $isCopyPasted
        ];
    }

    protected function buildPerDayBreakdown(Collection $entries): array
    {
        return $entries->values()->map(function ($entry, $i) {
            $issues = [];
            if ($this->isBlank($entry->procedure_a ?? null)) $issues[] = 'No instructional activities logged.';
            if ($this->isBlank($entry->procedure_e ?? null)) $issues[] = 'No formative assessment logged.';
            if ($this->isBlank($entry->reflection_notes ?? null)) $issues[] = 'No reflection notes.';

            return [
                'day'    => $i + 1,
                'date'   => $entry->date ?? null,
                'status' => empty($issues) ? 'complete' : 'incomplete',
                'issues' => $issues,
            ];
        })->toArray();
    }

    protected function computeComplianceScore(array $completeness, array $cognitive, array $history, int $totalDays): int
    {
        $activityRate     = 1 - ($completeness['missing_activities'] / $totalDays);
        $assessmentRate   = 1 - ($completeness['missing_assessments'] / $totalDays);
        $reflectionRate   = 1 - ($completeness['missing_reflections'] / $totalDays);

        $completenessRate = ($activityRate + $assessmentRate + $reflectionRate) / 3;

        $cognitiveScore = match ($cognitive['verdict']) {
            'hots'  => 1.0,
            'mots'  => 0.75,
            'lots'  => 0.5,
            default => 0.3,
        };

        $originalityScore = $history['is_copy_pasted'] ? 0.0 : 1.0;

        $score = ($completenessRate * $this->weights['completeness'])
            + ($assessmentRate * $this->weights['assessment'])
            + ($reflectionRate * $this->weights['reflection'])
            + ($cognitiveScore * $this->weights['cognitive_demand'])
            + ($originalityScore * $this->weights['originality']);

        return (int) round(max(0, min(100, $score)));
    }

    protected function scoreToRating(int $score): string
    {
        return match (true) {
            $score >= 90 => 'Highly Proficient',
            $score >= 75 => 'Proficient',
            $score >= 60 => 'Developing',
            default      => 'Needs Immediate Support',
        };
    }

    protected function buildBrief(int $totalDays, array $cognitive, array $completeness, array $history, int $score): string
    {
        $brief = "🧠 TrackEd Engine Scan: Teacher logged {$totalDays} day(s). ";

        if ($history['is_copy_pasted']) {
            $brief .= "⚠️ Red Flag: Activities are highly identical to the previous week's submission. ";
        }

        $brief .= match ($cognitive['verdict']) {
            'hots'  => "Competencies show strong High-Order Thinking (HOTS) alignment. ",
            'mots'  => "Competencies show moderate-order cognitive demand. ",
            'lots'  => "Competencies lean towards foundational recall (LOTS). ",
            default => "Cognitive level of competencies could not be determined. ",
        };

        if ($completeness['missing_activities'] > 0) {
            $brief .= "{$completeness['missing_activities']} day(s) lack instructional activities. ";
        }
        if ($completeness['missing_assessments'] > 0) {
            $brief .= "{$completeness['missing_assessments']} day(s) lack explicit formative assessments. ";
        }

        return $brief . "Overall compliance score: {$score}/100.";
    }

    protected function buildFeedback(array $completeness, array $cognitive, array $history, int $totalDays): string
    {
        if ($history['is_copy_pasted']) {
            return "Please review your daily procedures. They appear to be a direct duplicate of last week's lesson log. Ensure activities are updated for the current competencies.";
        }

        if ($completeness['missing_activities'] > 0) {
            return "Please detail your step-by-step instructional procedures for the missing day(s).";
        }

        if ($completeness['missing_assessments'] > 0) {
            return "Please specify your formative evaluation methods (e.g., 5-item quiz) for the missing day(s).";
        }

        if ($completeness['missing_reflections'] === $totalDays) {
            return "Reminder: Post-lesson reflections must be completed to finalize the instructional cycle.";
        }

        if ($cognitive['verdict'] === 'lots') {
            return "Consider revising at least one competency per week to require analysis, evaluation, or creation, rather than recall alone.";
        }

        return "Matrix is highly compliant and well-structured. Checked and noted.";
    }

    protected function identifyStrengths(array $completeness, array $cognitive, array $history): array
    {
        $strengths = [];
        if ($completeness['missing_assessments'] === 0) $strengths[] = 'All days have a mapped formative assessment.';
        if ($completeness['missing_reflections'] === 0) $strengths[] = 'Reflection notes are complete for every logged day.';
        if (in_array($cognitive['verdict'], ['hots', 'mots'])) $strengths[] = 'Competencies engage learners above the recall level.';
        if (!$history['is_copy_pasted'] && $completeness['missing_activities'] === 0) $strengths[] = 'Instructional activities are fully mapped and original.';
        return $strengths;
    }

    protected function identifyImprovements(array $completeness, array $cognitive, array $history): array
    {
        $improvements = [];
        if ($history['is_copy_pasted']) {
            $improvements[] = "Ensure instructional activities are unique to the current week's competencies and not duplicated from past logs.";
        }
        if ($completeness['missing_activities'] > 0) {
            $improvements[] = "{$completeness['missing_activities']} day(s) need detailed instructional procedures.";
        }
        if ($completeness['missing_assessments'] > 0) {
            $improvements[] = "{$completeness['missing_assessments']} day(s) need a stated formative assessment.";
        }
        if ($completeness['missing_reflections'] > 0) {
            $improvements[] = "{$completeness['missing_reflections']} day(s) need reflection notes.";
        }
        if (in_array($cognitive['verdict'], ['lots', 'undetermined'])) {
            $improvements[] = 'Raise the cognitive demand of stated competencies (e.g., shift from "define" to "evaluate" or "create").';
        }
        return $improvements;
    }

    protected function isBlank(?string $value): bool
    {
        return trim((string) $value) === '';
    }
}
