<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Http\Controllers\MobileAPi\OrderController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $guarded = array();


    protected $casts = [
        'status' => OrderStatus::class
    ];
    protected $appends = [
        'vendor', 'tourist_name', 'rating'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function delete()
    {
        DB::transaction(function () {
            if ($this->orderDetails)
                $this->orderDetails()->delete();

            parent::delete();
        });
    }
public function driver ()
{
    return $this->belongsTo(Driver::class);
}
    public function user()
    {
        return $this->morphTo();
    }


    public function tourist()
    {
        return $this->belongsTo(Tourist::class);
    }

    public function getVendorAttribute()
    {

//        $name = $this->user()->pluck('name')[0];
        $name = $this->user()->pluck('name');
        return $name;
    }

    public function getRatingAttribute()
    {

//        $name = $this->user()->pluck('total_rating')[0];
//        return (int)$name;

        $name = $this->user()->pluck('total_rating');
        return $name;
    }

    public function getTouristNameAttribute()
    {
        return $this->tourist()->pluck('name')[0];
    }

    public function scopeFilter(Builder $builder, $arrayOfData)
    {
        $filterOptions = array_merge([
            'start_date' => null,
            'end_date' => null,
            'status' => null,
            'user_type' => null,
            'country_id'=>null,


        ], $arrayOfData);
        $builder->when($arrayOfData['start_date'], function (Builder $builder, $value) {
            $builder->where('start_date', $value);
        });
        $builder->when($arrayOfData['end_date'], function (Builder $builder, $value) {
            $builder->where('end_date', $value);
        });

        $builder->when($arrayOfData['country_id'], function (Builder $builder, $value) {
            $builder->where('country_id', $value);
        });
        $builder->when($arrayOfData['status'], function (Builder $builder, $value) {
            $builder->where('status', $value);
        });

        if ($arrayOfData['user_type'] == "1") {
            $builder->when($arrayOfData['user_type'], function (Builder $builder) {
                $builder->where('user_type', "App\Models\Driver");
         });

        } elseif ($arrayOfData['user_type'] == "2") {
            $builder->when($arrayOfData['user_type'], function (Builder $builder) {
                $builder->where('user_type', "App\Models\Guides");
         });
        }

    }

    //
    //    public function getUserTypeAttribute()
    //    {
    //        return explode("\\", get_class($this->user_type))[2];
    //    }
//    protected function userType(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => explode("\\", get_class($this->user))[2],
//        );
//    }
}
