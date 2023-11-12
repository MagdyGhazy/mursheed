<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStatusOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-status-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders= Order::where('status',OrderStatus::notCompleted)->get();

        foreach ($orders as $order)
        {

            if( Carbon::parse($order->status)->diffInHours(Carbon::now())>=1 )
            {
                $order->delete();
            }
        }

    }

}
