<?php

namespace App\Observers;

use App\Jobs\Notify\AdPriceChangedNotifySubscribersJob;
use App\Models\Subscription;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        if (
            $subscription->isDirty('last_price')
            && $subscription->last_price
        ) {
            AdPriceChangedNotifySubscribersJob::dispatch($subscription);
        }
    }
}
