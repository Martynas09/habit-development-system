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
use App\Models\Plan_goal;
use App\Models\Plan_habit;
use App\Models\Reminder;
use DateTimeImmutable;
use DateInterval;

class PlanController extends Controller
{
    public function showPlans()
    {
        $plans = Plan::where('fk_user', auth()->user()->id)
            ->whereNotIn('title', ['Iššūkis'])
            ->get();
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
        //todo reikia validacijos ir paduot esamus goals kurie nepradėti
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
                        if ($request->reminder == 'system') {
                            $newPlanTask->fk_reminder = $newReminder->id;
                        } else {
                            $newPlanTask->fk_reminder = null;
                        }
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
            $newGoal->status = 'in progress';
            $newGoal->save();
            $newPlanGoal = new Plan_goal();
            $newPlanGoal->fk_plan = $plan->id;
            $newPlanGoal->fk_goal = $newGoal->id;
            $newPlanGoal->save();
            $goals[$newGoal->id] = $newGoal;
        }
        foreach ($request->habits as $habit) {
            $newHabit = new Habit();
            $newHabit->title = $habit['value'];
            $newHabit->save();
            $newPlanHabit = new Plan_habit();
            $newPlanHabit->fk_plan = $plan->id;
            $newPlanHabit->fk_habit = $newHabit->id;
            $newPlanHabit->save();
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
    public function showPlanEdit($id)
    {
        $goals = array();
        $habits = array();
        $tasks = array();
        $prizes = array();
        $plan = Plan::find($id);
        $reminders = false;

        //getting goals
        $goalsTemp = Plan_goal::where('fk_plan', $plan->id)->get()->load('goals');
        foreach ($goalsTemp as $goal) {
            $goals[] = $goal->goals;
        }

        //getting habits
        $habitsTemp = Plan_habit::where('fk_plan', $plan->id)->get()->load('habits');
        foreach ($habitsTemp as $habit) {
            $habits[] = $habit->habits;
        }

        //getting tasks list
        foreach ($plan->getTasks as $task) {
            if ($task->fk_reminder != null) {
                $reminders = true;
            }
            $title = $task->getTask;
            if (!in_array($title, $tasks)) {
                $tasks[] = $task->getTask;
            }
        }

        //getting tasks for each day
        $tasksByWeekday = array(
            'Monday' => array(),
            'Tuesday' => array(),
            'Wednesday' => array(),
            'Thursday' => array(),
            'Friday' => array(),
            'Saturday' => array(),
            'Sunday' => array(),
        );

        foreach ($plan->getTasks as $task) {
            $weekday = date('l', strtotime($task['execution_date']));
            $time = date('H:i', strtotime($task['execution_date']));
            $existingTask = null;

            // Look for existing task with same title, time and weekday
            foreach ($tasksByWeekday[$weekday] as $existing) {
                if (
                    $existing['title'] === $task['title'] &&
                    date('H:i', strtotime($existing['execution_date'])) === $time
                ) {
                    $existingTask = $existing;
                    break;
                }
            }
            if (!$existingTask) {
                // Add task to weekday array
                $tasksByWeekday[$weekday][] = $task;
            }
        }

        //getting prizes list
        $prizesTemp = Prize::where('fk_plan', $plan->id)->get()->load('plan', 'task', 'habit', 'goal');
        foreach ($prizesTemp as $prize) {
            $prizes[] = $prize;
        }


        return inertia::render('Plan/PlanEditView', [
            'plan' => $plan,
            'goals' => $goals,
            'habits' => $habits,
            'tasks' => $tasks,
            'tasksByWeekday' => $tasksByWeekday,
            'prizes' => $prizes,
            'reminders' => $reminders,
        ]);
    }
    public function editPlan(Request $request, $id)
    {
        $goals = [];
        $habits = [];
        $tasks = [];
        $plan = Plan::find($id);
        $plan->title = $request->title;
        $plan->color = $request->color;
        $plan->save();

        //deleting all reminders
        foreach ($plan->getTasks as $task) {
            if ($task->fk_reminder != null) {
                $task->getReminder->delete();
            }
        }

        //deleting only upcoming tasks
        foreach ($plan->getTasks->where('execution_date', '>', now())->load('getTask') as $task) {
            $task->getTask->delete();
        }
        $plan->getTasks()->where('execution_date', '>', now())->delete();



        //adding tasks and reminders(if selected system)
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
                        ->add(new DateInterval('PT3H'));
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
                        if ($request->reminder == 'system') {
                            $newPlanTask->fk_reminder = $newReminder->id;
                        } else {
                            $newPlanTask->fk_reminder = null;
                        }
                        $newPlanTask->fk_task = $newTask->id;
                        $newPlanTask->fk_plan = $plan->id;
                        $newPlanTask->save();
                    }
                }
            }
        }
        //removing all plan_goals
        $plan->getPlanGoals()->delete();

        //adding only new goals, and creating new plan_goals
        foreach ($request->goals as $goal) {
            // Check if a goal with the same title already exists in the database
            $existingGoal = Goal::where('title', $goal['value'])->first();

            // If the goal doesn't exist, create a new one
            if (!$existingGoal) {
                $newGoal = new Goal();
                $newGoal->title = $goal['value'];
                $newGoal->status = 'in progress';
                $newGoal->save();
            } else {
                $newGoal = $existingGoal;
            }
            $newPlanGoal = new Plan_goal();
            $newPlanGoal->fk_plan = $plan->id;
            $newPlanGoal->fk_goal = $newGoal->id;
            $newPlanGoal->save();
            $goals[$newGoal->id] = $newGoal;
        }

        //removing all plan_habits
        $plan->getPlanHabits()->delete();

        //adding only new habits, and creating new plan_habits
        foreach ($request->habits as $habit) {
            // Check if a habit with the same title already exists in the database
            $existingHabit = Habit::where('title', $habit['value'])->first();

            // If the habit doesn't exist, create a new one
            if (!$existingHabit) {
                $newHabit = new Habit();
                $newHabit->title = $habit['value'];
                $newHabit->save();
            } else {
                $newHabit = $existingHabit;
            }

            $newPlanHabit = new Plan_habit();
            $newPlanHabit->fk_plan = $plan->id;
            $newPlanHabit->fk_habit = $newHabit->id;
            $newPlanHabit->save();
            $habits[$newHabit->id] = $newHabit;
        }

        //deleting all prizes
        $plan->getPrizes()->delete();

        //adding new ones
        foreach ($request->prizes as $prize) {
            $newPrize = new Prize();
            $newPrize->title = $prize['title'];
            $newPrize->category = $prize['category'];
            if ($prize['category'] == 'goal') {
                foreach ($goals as $goal) {
                    if ($goal->title == $prize['receiverTitle']) {
                        $newPrize->fk_goal = $goal->id;
                    }
                }
            }
            if ($prize['category'] == 'habit') {
                foreach ($habits as $habit) {
                    if ($habit->title == $prize['receiverTitle']) {
                        $newPrize->fk_habit = $habit->id;
                    }
                }
            }
            if ($prize['category'] == 'task') {
                foreach ($tasks as $task) {
                    if ($task->title == $prize['receiverTitle']) {
                        $newPrize->fk_task = $task->id;
                    }
                }
            }
            $newPrize->fk_plan = $plan->id;
            $newPrize->save();
        }



        return Inertia::render('Plan/PlanEditView');
    }
    public function deletePlan($id)
    {
        $plan = Plan::find($id);
        //deleting all reminders
        foreach ($plan->getTasks as $task) {
            if ($task->fk_reminder != null) {
                $task->getReminder->delete();
            }
        }

        //deleting all tasks
        foreach ($plan->getTasks->load('getTask') as $task) {
            $task->getTask->delete();
        }
        $plan->getTasks()->delete();
        $plan->getPlanGoals()->delete();
        $plan->getPlanHabits()->delete();
        $plan->getPrizes()->delete();
        $plan->delete();
        return Inertia::render('Plan/PlanListView');
    }
}
