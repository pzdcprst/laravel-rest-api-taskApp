<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Enums\Role;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return ($user->id === $project->user_id) or ($user->role === Role::admin->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return ($user->id === $project->user_id) or ($user->role === Role::admin->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return ($user->id === $project->user_id) or ($user->role === Role::admin->value);
    }

    public function create(User $user): bool
    {
        return ($user->role === Role::admin->value) or ($user->role === Role::manager->value);
    }
}
