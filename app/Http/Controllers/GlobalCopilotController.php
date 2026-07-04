<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalCopilotController extends Controller
{
    public function handlePrompt(Request $request)
    {
        $promptId = $request->input('prompt_id');

        // Grab the user's role to make the AI feel personalized
        $role = Auth::user()->role ?? 'guest';

        $responses = [
            'system_nav' => "🗺️ **System Navigation**\n\nTrackEd is divided into specialized workspaces. Use the dark sidebar on the left to switch between your available modules. Your profile and account settings are located in the top-right dropdown menu.",

            'dll_help' => "📚 **Smart Lesson Logs (DLL)**\n\nThe Daily Matrix allows you to build lesson plans using either the ILAW or MATATAG framework. As you type your Intentions, you can click the '✨ Suggest' buttons to have my local engine generate DepEd-aligned pedagogies instantly!",

            'role_duty' => $this->getRoleDuty($role)
        ];

        // Fetch the corresponding answer, or a fallback if something goes wrong
        $reply = $responses[$promptId] ?? "I'm sorry, I don't have information on that topic right now.";

        return response()->json(['message' => $reply]);
    }

    /**
     * AI Heuristic: Customizes the answer based on who is logged in.
     */
    private function getRoleDuty(string $role): string
    {
        return match ($role) {
            'teacher' => "👥 **Faculty Responsibilities**\n\nAs a Teacher, your primary duties in TrackEd are to maintain your Daily Lesson Logs (DLL), log behavioral incidents in your classes, and manage your assigned classroom inventory.",

            'school_head', 'admin' => "👥 **Executive Responsibilities**\n\nAs a School Head, you are responsible for auditing submitted Daily Lesson Logs, providing Technical Assistance, and reviewing school-wide Decision Support System (DSS) analytics.",

            'counselor' => "👥 **Counselor Responsibilities**\n\nAs a Counselor, your workspace is focused on reviewing behavioral incident reports, managing interventions, and processing Good Moral certificate requests.",

            default => "👥 **System User**\n\nPlease refer to your department head for your specific administrative duties within the TrackEd platform."
        };
    }
}
