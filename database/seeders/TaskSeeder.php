<?php

namespace Database\Seeders;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = Status::cases();
        $priorities = Priority::cases();

        Project::query()->get()->each(function (Project $project) use ($statuses, $priorities): void {
            for ($taskNumber = 1; $taskNumber <= 3; $taskNumber++) {
                $status = $statuses[($project->id + $taskNumber - 1) % count($statuses)];
                $priority = $priorities[($project->id + $taskNumber - 1) % count($priorities)];

                Task::factory()->forProject($project)->create([
                    'status' => $status->value,
                    'priority' => $priority->value,
                ]);
            }
        });
    }
}
