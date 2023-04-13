<?php

namespace App\Http\Controllers;

use App\Models\Reflection_question;
use App\Models\Reflection_answer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function showReflectionQuestionsList()
    {
        $questions=Reflection_question::orderBy('number')->get()->load('answers');
        return Inertia::render('Questions', [
            'questions' => $questions,
        ]);
    }
    public function deleteReflectionQuestion(Request $request)
    {
        $question = Reflection_question::find($request->id);
        //find biggest number question
        $maxNumber = Reflection_question::max('number');
        if($question->number!=$maxNumber)
        {
            $tempQuestion=Reflection_question::where('number', $maxNumber)->first();
            $tempQuestion->number=$question->number;
            $tempQuestion->save();
        }
        $question->answers()->delete();
        $question->delete();
    }
    public function editReflectionQuestion(Request $request)
    {
        $question = Reflection_question::find($request->id);
        if($question->number!=$request->number){
            $tempQuestion=Reflection_question::where('number', $request->number)->first();
            $tempQuestion->number=$question->number;
            $tempQuestion->save();
        }
        $question->number=$request->number;
        $question->content=$request->content;
        $question->required=$request->required;
        $question->save();
    }
    public function editReflectionAnswer(Request $request)
    {
        $answer = Reflection_answer::find($request->id);
        $answer->content=$request->content;
        $answer->save();
    }
    public function deleteReflectionAnswer(Request $request)
    {
        $answer = Reflection_answer::find($request->id);
        $answer->delete();
    }
    public function addReflectionQuestion(Request $request)
    {
        if(Reflection_question::where('number', $request->number)->first()!=null)
        {
            $maxNumber = Reflection_question::max('number');
            $tempQuestion=Reflection_question::where('number', $request->number)->first();
            $tempQuestion->number=$maxNumber+1;
            $tempQuestion->save();
        }
        $question = new Reflection_question();
        $question->number=$request->number;
        $question->content=$request->content;
        $question->required=$request->required;
        $question->save();
        foreach($request->answers as $answer)
        {
            $newAnswer = new Reflection_answer();
            $newAnswer->content=$answer['value'];
            $newAnswer->fk_question=$question->id;
            $newAnswer->save();
        }
    }

}
