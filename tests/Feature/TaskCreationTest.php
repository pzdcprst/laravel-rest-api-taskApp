<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_for_their_project(): void
    {
        $user = User::factory()->create();
        $project = Project::create([
            'name' => 'Demo project',
            'description' => 'A sample project',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/projects/{$project->id}/tasks", [
                'title' => 'Header',
                'description' => 'в прежнем стиле',
                'priority' => 'low',
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Header')
            ->assertJsonPath('data.project.id', $project->id)
            ->assertDatabaseHas('tasks', [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'title' => 'Header',
            ]);
    }
}
