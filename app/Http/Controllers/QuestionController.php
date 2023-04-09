<?php

namespace App\Http\Controllers;

use App\Models\Reflection_question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function showReflectionQuestionsList()
    {
        $questions=Reflection_question::get()->load('answers');
        return Inertia::render('Questions', [
            'questions' => $questions,
        ]);
    }
}
