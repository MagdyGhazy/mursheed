<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Services\ReportServes;

class ReportController extends Controller
{
    public function __construct(public ReportServes $reportService)
    {
        $this->reportService = $reportService;
    }
    public function index(Request $request)
    {
        return $this->reportService->filter($request->all());
    }

    public function profits(Request $request)
    {
        return $this->reportService->Profits($request);
    }

    public function profitsFromSixMonths(Request $request)
    {
        $data['country_id'] = $request->country_id;
        $data['start_date'] = Carbon::now()->subMonths(6);
        $data['end_date'] = now();

        return $this->reportService->Profits($data);
    }
    
}
