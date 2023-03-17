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
        
        \App\Models\Level::factory()->create([
            'title' => 'Level 1',
            'requiredXP' => 0,
        ]);
        \App\Models\Level::factory()->create([
            'title' => 'Level 2',
            'requiredXP' => 100,
        ]);
        \App\Models\Level::factory()->create([
            'title' => 'Level 3',
            'requiredXP' => 200,
        ]);
        \App\Models\Level::factory()->create([
            'title' => 'Level 4',
            'requiredXP' => 300,
        ]);
        \App\Models\Level::factory()->create([
            'title' => 'Level 5',
            'requiredXP' => 400,
        ]);
        
        \App\Models\User::factory()->create([
            'username' => 'Test User',
            'email' => 'test@test',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => 'ZbwWAZpB5n',
            'xp' => 344,
            'avatar' => 'head1.png',
         ]);
        \App\Models\User::factory(15)->create();
        \App\Models\Reflection_question::factory(10)->create();
        \App\Models\Reflection_answer::factory(100)->create();
        \App\Models\Achievement::factory(10)->create();
        \App\Models\User_achievement::factory(15)->create();

        \App\Models\Character_item::factory()->create([
            'title' => 'Default Head',
            'picture' => 'head1.png',
            'rarity' => 'common',
            'category' => 'head',
            'fk_level' => 1,
        ]);
        \App\Models\Character_item::factory()->create([
            'title' => 'Default top',
            'picture' => 'top1.png',
            'rarity' => 'common',
            'category' => 'top',
            'fk_level' => 1,
        ]);
        \App\Models\Character_item::factory()->create([
            'title' => 'Default bottom',
            'picture' => 'bottom1.png',
            'rarity' => 'common',
            'category' => 'bottom',
            'fk_level' => 1,
        ]);
        \App\Models\Character_item::factory()->create([
            'title' => 'Default shoes',
            'picture' => 'shoes1.png',
            'rarity' => 'common',
            'category' => 'shoes',
            'fk_level' => 1,
        ]);

        \App\Models\Character_item::factory(7)->create();
        \App\Models\Note::factory(40)->create();
        \App\Models\Challenge::factory(5)->create();
        \App\Models\Challenged_user::factory(10)->create();
        \App\Models\Plan::factory()->create([
            'title' => 'Test Plan',
            'active' => 1,
            'fk_user' => 1,
        ]);
        \App\Models\Plan::factory(20)->create();
        \App\Models\Goal::factory(25)->create();
        \App\Models\Habit::factory(20)->create();
        \App\Models\Plan_habit::factory(10)->create();
        \App\Models\Plan_goal::factory(10)->create();
        \App\Models\Reminder::factory(100)->create();
        \App\Models\Task::factory(50)->create();
        \App\Models\Prize::factory(25)->create();
        \App\Models\Plan_task::factory(70)->create();

        \App\Models\Users_character::factory()->create([
            'head' => 1,
            'top' => 2,
            'bottom' => 3,
            'shoes' => 4,
            'fk_user' => 1,
        ]);

        //\App\Models\Users_character::factory(15)->create();
    }
}
