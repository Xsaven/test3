<?php

namespace App\Jobs;

use App\Interfaces\AdRequestServiceInterface;
use App\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAdPriceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Subscription $subscription,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = app(AdRequestServiceInterface::class);

        $service->setUrl(
            $this->subscription->ad_url
        );

        $this->subscription->update([
            'last_price' => $service->getAdPrice(),
        ]);
    }
}
