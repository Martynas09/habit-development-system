<?php

namespace App\Http\Controllers;

use App\Models\Plan_task;
use App\Models\Reflection_question;
use App\Models\Reflection_answer;
use App\Models\Plan;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ReflectionController extends Controller
{
    public function showReflection()
    {
        $questions = Reflection_question::orderBy('number')->get();
        $answers = Reflection_answer::all();
        $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->get();
        return inertia::render('Reflection', [
            'questionData' => $questions,
            'answerData' => $answers,
            'planData' => $plans
        ]);
    }
    public function reflectionFinished(Request $request)
    {
      dd($request->all());
      //idea: make refleksija plan for each user and hide it everywhere and use it only to store reflection tasks
        $plan = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->first();
        $task = new Task();
        $task->duration = 15;
        $task->title = "Refleksija";
        $task->save();
        //adding reflection as a task
        $reflection = new Plan_task();
        $reflection->fk_plan = $plan->id;
        $reflection->fk_task = $task->id;
        $reflection->execution_date = Carbon::now('Europe/Vilnius');
        $reflection->is_done = 1;
        $reflection->save();
        return Redirect::route('Schedule');
    }
}
