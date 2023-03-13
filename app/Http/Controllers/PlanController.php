<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
use App\Models\Task;
use DateTimeImmutable;
use DateInterval;

class PlanController extends Controller
{
    public function showPlans()
    {
        $plans = Plan::where('fk_user', auth()->user()->id)->get();
        return inertia::render('Plan/PlanListView', [
            'plans' => $plans
        ]);
    }
    public function showPlan($id)
    {
        $plan = Plan::where('id', $id)->first()->load('getTasks.getTask');
        return inertia::render('Plan/PlanView', [
            'plan' => $plan
        ]);
    }
    public function taskDone(Request $request)
    {
        $task = Plan_task::where('id', $request->id)->first();
        $task->is_done = 1;
        $task->save();
    }
    public function showAlternatives()
    {
        return inertia::render('Plan/ChooseAlternativeView');
    }
    public function showQuestionnaire()
    {
        return inertia::render('Plan/QuestionnaireView');
    }
    public function showCustom()
    {
        return inertia::render('Plan/CustomView');
    }
    public function createPlan(Request $request)
    {
        //reikia validacijos
        $plan = new Plan();
        $plan->fk_user = auth()->user()->id;
        $plan->title = $request->title;
        $plan->active = 1;
        $plan->save();
        foreach ($request->tasks as $task) {
            $newTask = new Task();
            $newTask->title = $task['task'];
            $newTask->duration = $task['duration'];
            $newTask->save();
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($daysOfWeek as $day) {
                $taskTime = null;

                //check if task is in $day array
                foreach ($request->$day as $checkDay) {
                    if (in_array($task['task'], $checkDay)) {
                        $taskTime = $checkDay['time'];
                        break;
                    }
                }
                if ($taskTime) {
                    $dateTime = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.u\Z', $taskTime)
                        ->add(new DateInterval('PT2H'));
                    $hoursAndMinutes = $dateTime->format('H:i');
                    for ($i = 0; $i < 4; $i++) {
                        $upcomingDay = new DateTimeImmutable('next ' . ucfirst($day) . ' +' . $i . ' week');
                        $tempDate = $upcomingDay->format('Y-m-d') . ' ' . $hoursAndMinutes;
                        $newPlanTask = new Plan_task();
                        $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
                        $newPlanTask->is_done = 0;
                        $newPlanTask->fk_reminder = 1;
                        $newPlanTask->fk_task = $newTask->id;
                        $newPlanTask->fk_plan = $plan->id;
                        $newPlanTask->save();
                    }
                }
            }
        }
        return Inertia::render('Plan/CustomView',[ 'plan_id' => $plan->id]);

    }
}
