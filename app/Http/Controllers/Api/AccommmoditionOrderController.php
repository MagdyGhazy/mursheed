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
        $data = OrderAccommmodition::with('category','tourist')->get();
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly To Get All Data"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function filter(Request $request)
    {
        // $employees = OrderAccommmodition::orderBy('id', 'desc')
        // ->when(
        //     $request->date_from && $request->date_to,
        //     function (Builder $builder) use ($request) {
        //         $builder->whereBetween(
        //             DB::raw('DATE(created_at)'),
        //             [
        //                 $request->date_from,
        //                 $request->date_to
        //             ]
        //         );
        //     }
        // )->paginate(5);
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
        $data = OrderAccommmodition::where('id',$id)->with('category','tourist')->get();
        return response()->json([
            "data" => $data,
            "stutes" => "successfuly To Add"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
