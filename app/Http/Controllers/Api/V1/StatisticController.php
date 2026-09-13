<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    private StatisticService $statisticService;

    public function __construct()
    {
        $this->statisticService = new StatisticService;
    }

    public function statistics(Request $request)
    {
        $user = $request->user();

        return response()->json($this->statisticService->getStatistics($user));
    }
}
