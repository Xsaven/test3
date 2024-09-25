<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Subscription extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "subscriptions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ["ad_url", "last_price"];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ["last_price" => "float"];

    /**
     * Get the Subscribers that owns the Subscriber.
     *
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            Subscriber::class,
            "subscriptions_subscribers",
            "subscription_id",
            "subscriber_id",
        );
    }
}
