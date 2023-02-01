<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'username' => 'Test User',
            'email' => 'test@test',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => 'ZbwWAZpB5n',
         ]);
        \App\Models\User::factory(15)->create();
        \App\Models\Reflection_question::factory(10)->create();
        \App\Models\Reflection_answer::factory(100)->create();
        \App\Models\Achievement::factory(10)->create();
        \App\Models\User_achievement::factory(15)->create();
        \App\Models\Level::factory(10)->create();
        \App\Models\Character_item::factory(15)->create();
        \App\Models\Note::factory(40)->create();
        \App\Models\Challenge::factory(5)->create();
        \App\Models\Challenged_user::factory(10)->create();
        \App\Models\Plan::factory(20)->create();
        \App\Models\Prize::factory(25)->create();
        \App\Models\Plan_prize::factory(15)->create();
        \App\Models\Goal::factory(25)->create();
        \App\Models\Habit::factory(20)->create();
        \App\Models\Plan_habit::factory(10)->create();
        \App\Models\Plan_goal::factory(10)->create();
        \App\Models\Reminder::factory(100)->create();
        \App\Models\Task::factory(50)->create();
        \App\Models\Plan_task::factory(70)->create();
        
    }
}
