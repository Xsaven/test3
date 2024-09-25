<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Subscriber;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * Subscribe to the ad.
     *
     * @param  \App\Http\Requests\SubscribeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $subscriber = Subscriber::query()
            ->firstOrCreate(
                ['email' => $request->email],
            );

        $subscription = Subscription::query()
            ->firstOrCreate(
                ['ad_url' => $request->ad_url],
            );

        $subscriber->confirm();

        $subscriber->subscriptions()->syncWithoutDetaching($subscription);

        if ($subscriber->confirmed) {

            return response()->json(['message' => 'Підписка оформлена!']);
        }

        return response()->json(['message' => 'Підписка оформлена! Перевірте електронну пошту для підтвердження.']);
    }
}
