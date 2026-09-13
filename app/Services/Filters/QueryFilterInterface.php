<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface QueryFilterInterface
{
    public function apply(Builder $query, Request $request): Builder;
}
