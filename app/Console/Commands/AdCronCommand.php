<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the ad cron job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {

            exec('php artisan schedule:run');

            sleep(60);
        }
    }
}
