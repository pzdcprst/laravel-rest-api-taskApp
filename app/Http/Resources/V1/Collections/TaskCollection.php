<?php

namespace App\Http\Resources\V1\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V1\TaskResource;

class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public $collects = TaskResource::class;
    
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
