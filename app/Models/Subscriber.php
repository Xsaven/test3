<?php

namespace App\Models;

use App\Jobs\Notify\SubscriberConfirmNotifyJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;


class Subscriber extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "subscribers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ["email", "confirmed", "confirmation_token"];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ["confirmed" => "boolean"];

    /**
     * Get the subscriptions that owns the Subscription.
     *
     * @return BelongsToMany
     */
    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            Subscription::class,
            "subscriptions_subscribers",
            "subscriber_id",
            "subscription_id",
        );
    }

    /**
     * Confirm the subscriber.
     *
     * @return void
     */
    public function confirm(): void
    {
        if (! $this->confirmed) {

            $token = Str::random(60);

            $this->update([
                "confirmation_token" => $token,
            ]);

            SubscriberConfirmNotifyJob::dispatch($this);
        }
    }
}
