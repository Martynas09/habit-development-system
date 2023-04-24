<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Models\User;
use App\Notifications\TaskReminder;
use Carbon\Carbon;

class Reminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = Reminder::where('remind_time', '<=', Carbon::now('Europe/Vilnius')->addMinutes(15))
        ->where('remind_time', '>', Carbon::now('Europe/Vilnius'))
        ->where('is_sent', 0)
        ->get();
        foreach($reminders as $reminder){
            $minutes = Carbon::now('Europe/Vilnius')->diffInMinutes($reminder->remind_time);
            $title=$reminder->planTask->getTask->title;
            $user = User::where('id', $reminder->planTask->getPlan->fk_user)->first();
            $user->notify(new TaskReminder($title,$minutes));
            $reminder->is_sent = 1;
            $reminder->save();
        }
    }
}
