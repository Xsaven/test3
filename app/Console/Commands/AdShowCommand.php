<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class AdShowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show an ad';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscription = Subscription::query()
            ->whereHas('subscribers', function ($query) {
                $query->where('confirmed', true);
            })->get();

        $this->table(
            ['Ad URL', 'Last Price'],
            $subscription->map(function (Subscription $subscription) {
                return [
                    $subscription->ad_url,
                    number_format($subscription->last_price, 2, '.', ' '),
                ];
            })
        );
    }
}
