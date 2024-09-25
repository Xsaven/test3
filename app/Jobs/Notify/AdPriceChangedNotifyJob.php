<?php

namespace App\Jobs\Notify;

use App\Mail\AdPriceChangedNotification;
use App\Models\Subscriber;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class AdPriceChangedNotifyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Subscriber $subscriber,
        protected Subscription $subscription,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new AdPriceChangedNotification($this->subscription));
    }
}
