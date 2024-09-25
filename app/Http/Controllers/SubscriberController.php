<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    /**
     * Confirm the subscriber.
     *
     * @param  string  $token
     * @return View
     */
    public function confirm(string $token): View
    {
        $subscriber = Subscriber::query()
            ->where('confirmation_token', $token)
            ->firstOrFail();

        $subscriber->confirmed = true;
        $subscriber->confirmation_token = null;
        $subscriber->save();

        return view('confirmation.subscriber-confirmed');
    }
}
