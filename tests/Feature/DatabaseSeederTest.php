<?php

namespace Tests\Feature;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_sample_data_with_consistent_relations(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 5);
        $this->assertDatabaseCount('projects', 10);
        $this->assertDatabaseCount('tasks', 30);

        $projects = Project::query()->with('tasks')->get();

        foreach ($projects as $project) {
            $this->assertCount(3, $project->tasks);

            foreach ($project->tasks as $task) {
                $this->assertSame($project->user_id, $task->user_id);
            }
        }

        $this->assertEqualsCanonicalizing(
            Status::values(),
            Task::query()->distinct()->pluck('status')->all(),
        );
        $this->assertEqualsCanonicalizing(
            Priority::values(),
            Task::query()->distinct()->pluck('priority')->all(),
        );
    }
}
