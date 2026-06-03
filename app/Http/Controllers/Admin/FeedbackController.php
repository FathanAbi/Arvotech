<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::latest()
            ->paginate(10);

        $totalFeedback = Feedback::count();

        $totalCriticisms = Feedback::where(
            'category',
            'kritik'
        )->count();

        $totalSuggestions = Feedback::where(
            'category',
            'saran'
        )->count();

        return view(
            'admin.feedback.index',
            compact(
                'feedbacks',
                'totalFeedback',
                'totalCriticisms',
                'totalSuggestions'
            )
        );
    }

    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);

        $feedback->update([
            'is_read' => true
        ]);

        return view(
            'admin.feedback.show',
            compact('feedback')
        );
    }
}