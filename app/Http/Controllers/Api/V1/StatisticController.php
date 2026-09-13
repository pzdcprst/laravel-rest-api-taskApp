<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StatisticService;

class StatisticController extends Controller
{
    private StatisticService $statisticService;

    public function __construct()
    {
        $this->statisticService = new StatisticService();
    }

    public function statistics(Request $request)
    {
        $user = $request->user();

        return response()->json($this->statisticService->getStatistics($user));
    }
}
