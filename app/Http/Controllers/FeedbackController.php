<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required'
        ]);

        Feedback::create($request->all());

        return back()
            ->with('success', 'Terima kasih atas masukan Anda');
    }
}