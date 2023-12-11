<?php

namespace App\Http\Controllers\Api;

use order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderAccommmodition;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class AccommmoditionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = OrderAccommmodition::with('tourist', 'touaccommdation')->get();
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly get  all"
        ]);
    }


    public function filter(Request $request)
    {
        $data = OrderAccommmodition::when(
            $request->start_date && $request->end_date,
            function (Builder $builder) use ($request) {
                $builder->whereBetween(
                    DB::raw('DATE(start_date)'),
                    [
                        $request->start_date,
                        $request->end_date
                    ]
                );
            }
        )->paginate(5);
        $data = OrderAccommmodition::with('category', 'tourist')->get();
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly To Get All Data"
        ]);
    }


    public function store(Request $request)
    {
        $data = OrderAccommmodition::create($request->all());
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly To Add"
        ]);
    }

    public function show(string $id)
    {
        $data = OrderAccommmodition::where('id', $id)->with('category', 'tourist')->get();
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly To Add"
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
