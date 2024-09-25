<?php

namespace App\Jobs\Notify;

use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AdPriceChangedNotifySubscribersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Subscription $subscription
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->subscription->subscribers()->where('confirmed', true)->get()->each(function ($subscriber) {
            AdPriceChangedNotifyJob::dispatch($subscriber, $this->subscription);
        });
    }
}
