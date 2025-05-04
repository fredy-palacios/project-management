<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Event;


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $projects = Project::all();

        foreach ($users as $user) {
            $project = $projects->random();

            Event::create([
                'info' => 'Event for ' . $user->name . ' in project ' . $project->name,
                'user_id' => $user->id,
                'project_id' => $project->id,
                'date_from' => now(),
                'date_to' => now()->addDays(7),
            ]);
        }
    }
}
