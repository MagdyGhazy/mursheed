<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Models\Driver;
use App\Models\Guides;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{

    public function index(Request $request)
    {
        $reviews = DB::table('reviews')->where('reviewable_id', $request->user_id)->count();
        return response()->json([
            "reviews" => $reviews
        ]);
    }

    public function store(StoreRatingRequest $request)
    {
        return response()->json("sssssssss");
        $data = $request->except("reviewable_id", "type");
        $data['tourist_id'] = $request->user()->user_id;

        $reviewable_id = $request->validated("reviewable_id");
        $type = $request->validated("type");

        $reviewable = null;
        if ($type == 0) {
            $reviewable = Driver::findOrFail($reviewable_id);
        }

        if ($type == 1) {
            $reviewable = Guides::findOrFail($reviewable_id);
        }

        try {

            $reviewable->reviews()->create($data);

            if ($reviewable->ratings_count == 0 || $reviewable->ratings_sum == 0) {

                $reviewable->ratings_sum = $reviewable->reviews()->sum('tourist_rating');

                $reviewable->ratings_count = $reviewable->reviews()->count();
            }

            $reviewable->ratings_count++;
            $reviewable->ratings_sum += $request->validated('tourist_rating');


            if ($reviewable->admin_rating > 0) {

                $rating = ($reviewable->ratings_sum + ($reviewable->admin_rating * $reviewable->ratings_count)) / ($reviewable->ratings_count * 2);

                $reviewable->total_rating = $rating > 5 ? 5 : $rating;

                $reviewable->save();

                return response()->json([
                    "message" => "Review successfully created",
                    "rating" => round($reviewable->total_rating, 1),

                ]);
            }

            $reviewable->total_rating = $reviewable->ratings_sum / $reviewable->ratings_count;
            $reviewable->save();

            return response()->json([
                "message" => "Review successfully created",
                "rating" => round($reviewable->total_rating, 1),

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Review successfully created",
                "error" => $th->getMessage(),
            ]);
        }
    }


    public function createAdminRating(StoreRatingRequest $request)
    {

        $data = $request->except("reviewable_id", "type");
        $data['tourist_id'] = $request->user()->user_id;

        $reviewable_id = $request->validated("reviewable_id");
        $type = $request->validated("type");

        $reviewable = null;
        if ($type == 0) {
            $reviewable = Driver::findOrFail($reviewable_id);
        }

        if ($type == 1) {
            $reviewable = Guides::findOrFail($reviewable_id);
        }

        $reviewable->update([
            'admin_rating' => $request->validated('tourist_rating')
        ]);

        if ($reviewable->ratings_count==null) {
            $reviewable->total_rating = $reviewable->admin_rating ;
            $reviewable->save();
        } else {
            $rating = ($reviewable->total_rating + ($reviewable->admin_rating * $reviewable->ratings_count)) / ($reviewable->ratings_count * 2);
            $reviewable->total_rating = $rating > 5 ? 5 : $rating;
            $reviewable->save();
        }


        return response()->json([
            "message" => "Review successfully created",
            "rating" => $reviewable->fresh(),

        ]);
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
