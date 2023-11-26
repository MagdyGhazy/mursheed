<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
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

    public function profits(Request $request , $country_id = null)
    {
        return $this->reportService->Profits($request , $country_id);
    }

}
