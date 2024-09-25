<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class AdResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset an ad';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscription = Subscription::query()
            ->whereHas('subscribers', function ($query) {
                $query->where('confirmed', true);
            })->get();

        $subscription->each(function (Subscription $subscription) {
            $subscription->update(['last_price' => 0]);
        });

        $this->info('All ads have been reset');
    }
}
