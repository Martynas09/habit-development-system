<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
use App\Models\Task;
use App\Models\Prize;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\Reminder;
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
        //todo reikia validacijos
        $goals = [];
        $habits = [];
        $tasks = [];
        $plan = new Plan();
        $plan->fk_user = auth()->user()->id;
        $plan->title = $request->title;
        $plan->color = $request->color;
        $plan->active = 1;
        $plan->save();
        foreach ($request->tasks as $task) {
            $newTask = new Task();
            $newTask->title = $task['value'];
            $newTask->duration = $task['duration'];
            $newTask->save();
            $tasks[$newTask->id] = $newTask;
            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($daysOfWeek as $day) {
                $taskTime = null;

                //check if task is in $day array
                foreach ($request->$day as $checkDay) {
                    if (in_array($task['value'], $checkDay)) {
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
                        if ($request->reminder == 'system') {
                            $newReminder = new Reminder();
                            $newReminder->remind_time = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
                            $newReminder->save();
                        }
                        $newPlanTask = new Plan_task();
                        $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
                        $newPlanTask->is_done = 0;
                        $newPlanTask->fk_reminder = $newReminder->id;
                        $newPlanTask->fk_task = $newTask->id;
                        $newPlanTask->fk_plan = $plan->id;
                        $newPlanTask->save();
                    }
                }
            }
        }
        foreach ($request->goals as $goal) {
            $newGoal = new Goal();
            $newGoal->title = $goal['value'];
            $newGoal->status = 'not in progress';
            $newGoal->save();
            $goals[$newGoal->id] = $newGoal;
        }
        foreach ($request->habits as $habit) {
            $newHabit = new Habit();
            $newHabit->title = $habit['value'];
            $newHabit->save();
            $habits[$newHabit->id] = $newHabit;
        }
        foreach ($request->prizes as $prize) {
            $newPrize = new Prize();
            $newPrize->title = $prize['title'];
            $newPrize->category = $prize['category'];
            if ($prize['category'] == 'goal') {
                foreach ($goals as $goal) {
                    if ($goal['title'] == $prize['receiverTitle']) {
                        $newPrize->fk_goal = $goal['id'];
                        break;
                    }
                }
            }
            if ($prize['category'] == 'habit') {
                foreach ($habits as $habit) {
                    if ($habit['title'] == $prize['receiverTitle']) {
                        $newPrize->fk_habit = $habit['id'];
                        break;
                    }
                }
            }
            if ($prize['category'] == 'task') {
                foreach ($tasks as $task) {
                    if ($task['title'] == $prize['receiverTitle']) {
                        $newPrize->fk_task = $task['id'];
                        break;
                    }
                }
            }

            $newPrize->fk_plan = $plan->id;
            $newPrize->save();
        }
        return Inertia::render('Plan/CustomView');
    }
}
