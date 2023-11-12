<?php

namespace App\Enums;

use App\Http\Traits\EnumToArray;


enum OrderStatus: string
{
    use EnumToArray;

    case notCompleted = '7';
    case  pending = '1';
    case  approved = '2';
    case  rejected = '3';
    case  expired = '4';
    case  canceled = '5';
    case  paid = '6';


//    public function lang():string
//    {
//        return match($this){
//            self::autism=>__('lookups.autism'),
//            self::neurologists=>__('lookups.neurologists'),
//        };
//    }

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if( $name === $status->value ){
                return $status->name;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }

}
