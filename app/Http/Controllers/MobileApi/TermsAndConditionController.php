<?php

namespace App\Http\Controllers\MobileApi;

use Illuminate\Http\Request;
use App\Models\TermsAndCondition;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;

class TermsAndConditionController extends Controller
{
    private $TermsAndCondition;
    private $ControllerHandler;
    public function __construct()
    {
        $this->TermsAndCondition = new TermsAndCondition();
        $this->ControllerHandler = new ControllerHandler($this->TermsAndCondition);
    }
    public function index()
    {
        return $this->ControllerHandler->getAllWith("TermsAndCondition", ['media']);
    }
}
