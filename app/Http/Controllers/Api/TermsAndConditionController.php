<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\TermsAndCondition;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHandler;
use App\Http\Requests\TermsAndConditionRequest;
use App\Http\Resources\TermsAndConditionResource;

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
    public function show($id)
    {
        $Condition = TermsAndCondition::with('media')->find($id);

        if ($Condition) {
            return response([
                "data" => new TermsAndConditionResource($Condition),
                "message" => "Success",
                "status" => true,
            ], 200);
        }

        return response([
            "data" => null,
            "message" => "Not Found",
            "status" => false,
        ], 404);
    }
    public function store(TermsAndConditionRequest $request)
    {
        $validated = $request->validated();
        $Condition = TermsAndCondition::create($request->except('file'));
        $Condition->addMediaFromRequest('file')->toMediaCollection('Terms And Condition');

        if ($Condition) {
            return response([
                "data" => new TermsAndConditionResource($Condition),
                "message" => "Success",
                "status" => true,
            ], 200);
        }

        return response([
            "data" => null,
            "message" => "Not Save",
            "status" => false,
        ], 400);
    }
    public function update(TermsAndConditionRequest $request, $id)
    {
        $validated = $request->validated();
        $Condition = TermsAndCondition::find($id);

        if (!$Condition) {
            return response([
                "data" => null,
                "message" => "Not Found",
                "status" => false,
            ], 404);
        }

        $Condition->clearMediaCollection('Terms And Condition');
        $Condition->update(['lang' => $request->lang]);
        $Condition->addMediaFromRequest('file')->toMediaCollection('Terms And Condition');

        return response([
            "data" => new TermsAndConditionResource($Condition),
            "message" => "Updated Success",
            "status" => true,
        ], 200);
    }

    public function destroy($id)
    {
        $Condition = TermsAndCondition::find($id);
        $Condition->delete();

        if ($Condition) {
            return response([
                "data" => null,
                "message" => "Deleted Success",
                "status" => true,
            ], 200);
        }

        return response([
            "data" => null,
            "message" => "Not Found",
            "status" => false,
        ], 404);
    }
}
