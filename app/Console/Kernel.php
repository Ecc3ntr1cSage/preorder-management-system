<?php

namespace App\Console;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Schema;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function(){
            if (Schema::hasTable('campaigns')) {
                $campaigns = Campaign::with('orders')
                    ->where('end_date', '<', now()) 
                    ->where('status', 1) 
                    ->get();

                foreach ($campaigns as $campaign) {
                    $campaign->update(['status' => 2]);
    

                    $campaign->orders()->update(['status' => 2]);
                }
            }
        })->name('update_campaign_status')->daily()->timezone('Asia/Singapore')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
