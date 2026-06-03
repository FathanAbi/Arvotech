<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'message' => 'required'
        ]);

        Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'category' => $request->category,
            'message' => $request->message
        ]);

        return back()->with(
            'success',
            'Terima kasih atas kritik dan saran Anda.'
        );
    }
}