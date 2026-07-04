<?php

namespace App\Services;

class TrackEdCoTeacher
{
    /**
     * Generates a highly contextual pedagogical suggestion based on the subject and the teacher's goal.
     */
    public function generateSuggestion(string $subject, string $intentions, string $type): string
    {
        $subject = strtolower(trim($subject));

        // 1. AI "Thinking" Step: Extract the core topic
        $topic = $this->extractCoreTopic($intentions);

        // 2. AI "Thinking" Step: Detect cognitive demand
        $cognitiveLevel = $this->detectCognitiveLevel($intentions);

        // 3. Route to the appropriate dynamic generator
        if ($type === 'learning_experience') {
            return $this->buildActivity($subject, $topic, $cognitiveLevel);
        }

        return $this->buildAssessment($subject, $topic, $cognitiveLevel);
    }

    /**
     * NLP Heuristic: Strips pedagogical filler words to find the actual subject matter.
     * Example: "To understand the process of photosynthesis" -> "process of photosynthesis"
     */
    protected function extractCoreTopic(string $intentions): string
    {
        $clean = strtolower($intentions);

        // Phrases to aggressively remove
        $fillerPhrases = [
            '/^the (learner|learners|student|students) will (be able to )?/i',
            '/^(to )?(understand|learn|know|comprehend|identify|discuss|analyze|evaluate|create|describe) /i',
            '/\b(about|how to|the concept of)\b/i'
        ];

        foreach ($fillerPhrases as $pattern) {
            $clean = preg_replace($pattern, '', $clean);
        }

        return trim($clean) ?: 'the core concepts'; // Fallback if they only typed a single word
    }

    /**
     * Maps the teacher's verbs to Bloom's Taxonomy to adjust the difficulty of the suggestion.
     */
    protected function detectCognitiveLevel(string $intentions): string
    {
        $intentions = strtolower($intentions);

        $hots = ['create', 'design', 'evaluate', 'judge', 'analyze', 'compare', 'formulate'];
        $lots = ['define', 'list', 'memorize', 'identify', 'state', 'name'];

        foreach ($hots as $verb) { if (str_contains($intentions, $verb)) return 'HOTS'; }
        foreach ($lots as $verb) { if (str_contains($intentions, $verb)) return 'LOTS'; }

        return 'MOTS'; // Default to application/understanding
    }

    /**
     * ==========================================
     * DYNAMIC ACTIVITY BUILDERS
     * ==========================================
     */
    protected function buildActivity(string $subject, string $topic, string $level): string
    {
        // Randomize groupings so the AI feels dynamic if clicked multiple times
        $groupings = ['in pairs', 'in small triads', 'in groups of four', 'collaboratively'];
        $group = $groupings[array_rand($groupings)];

        if (str_contains($subject, 'math')) {
            if ($level === 'HOTS') {
                return "CPA Approach (Abstract): Present an open-ended, real-world scenario involving {$topic}. Have students work {$group} to formulate multiple mathematical pathways to solve it, then defend their chosen method to the class.";
            }
            return "Guided Practice (I Do, We Do, You Do): Demonstrate the step-by-step calculation for {$topic} on the board. Next, solve a similar problem {$group}. Finally, have students attempt one independently.";
        }

        if (str_contains($subject, 'science')) {
            if ($level === 'LOTS') {
                return "5E Model (Engage): Start with a discrepant event or a short video clip related to {$topic}. Ask students to write down 2 observations and 1 question they have before starting the formal lecture.";
            }
            return "5E Model (Explore & Explain): Set up learning stations. Have students work {$group} to manipulate models or data related to {$topic}. Ask them to construct their own explanations before you provide the scientific definitions.";
        }

        if (str_contains($subject, 'english') || str_contains($subject, 'filipino')) {
            return "Contextualized Application: Provide a localized reading text or visual prompt. Have students work {$group} to analyze how {$topic} is applied in the material, then task them with producing a short, original output using the same structure.";
        }

        if (str_contains($subject, 'values') || str_contains($subject, 'esp') || str_contains($subject, 'ap') || str_contains($subject, 'araling panlipunan')) {
            return "4As Framework (Activity & Analysis): Initiate a short role-play or present a localized dilemma regarding {$topic}. Follow this with a processing discussion where students work {$group} to answer: 'What happened?', 'Why did it happen?', and 'How does this reflect our core values?'";
        }

        if (str_contains($subject, 'tle') || str_contains($subject, 'mapeh')) {
            return "Experiential Learning: Conduct a brief demonstration of the proper techniques for {$topic}. Immediately transition into a supervised practicum where students attempt the skill {$group} while you provide real-time corrective feedback.";
        }

        // The ultimate fallback
        return "Interactive Jigsaw: Break the concept of {$topic} into smaller chunks. Assign one chunk to each student {$group}. Give them 10 minutes to master their specific part, then have them teach it to their peers.";
    }

    /**
     * ==========================================
     * DYNAMIC ASSESSMENT BUILDERS
     * ==========================================
     */
    protected function buildAssessment(string $subject, string $topic, string $level): string
    {
        if ($level === 'HOTS') {
            return "Performance Task (Authentic Assessment): Ask students to produce a localized output or solve a case study applying {$topic}. Grade the output using a 4-point holistic rubric focusing on critical thinking and application rather than pure recall.";
        }

        if (str_contains($subject, 'math') || str_contains($subject, 'science')) {
            return "Formative Assessment (Data-Driven): Administer a 3-to-5 item short quiz specifically targeting {$topic}. Require students to show their step-by-step logic. Collect papers immediately to check for common misconceptions before the period ends.";
        }

        if (str_contains($subject, 'english') || str_contains($subject, 'filipino')) {
            return "Peer Review & Constructive Critique: Have students exchange their written outputs regarding {$topic}. Provide them with a simple 3-point checklist to evaluate their partner's work, fostering collaborative assessment.";
        }

        // Default quick formative check
        return "Exit Ticket (3-2-1 Method): Five minutes before the bell, ask students to write on a half-sheet of paper: 3 facts they learned about {$topic}, 2 interesting concepts, and 1 question they still have.";
    }
}
