<?php

namespace App\Http\Resources\V1\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V1\ProjectResource;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */

    public $collects = ProjectResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
