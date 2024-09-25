<?php

namespace App\Jobs\Notify;

use App\Mail\ConfirmSubscriberNotification;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SubscriberConfirmNotifyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Subscriber $subscriber
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new ConfirmSubscriberNotification(route('subscriptions.confirm', $this->subscriber->confirmation_token)));
    }
}
