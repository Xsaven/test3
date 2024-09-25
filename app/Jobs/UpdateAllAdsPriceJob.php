<?php

namespace App\Jobs;

use App\Interfaces\AdRequestServiceInterface;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAllAdsPriceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscriptions = Subscription::query()
            ->whereHas('subscribers', function ($query) {
                $query->where('confirmed', true);
            })->get();

        $subscriptions->each(function (Subscription $subscription) {
            UpdateAdPriceJob::dispatch($subscription);
        });
    }
}
