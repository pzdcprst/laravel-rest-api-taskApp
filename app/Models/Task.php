<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Override;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'project_id',
        'user_id',
        'due_date',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'priority' => Priority::class,
        ];
    }
}
