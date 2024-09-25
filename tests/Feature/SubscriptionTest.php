<?php

namespace Tests\Feature;

use App\Interfaces\AdRequestServiceInterface;
use App\Jobs\UpdateAdPriceJob;
use App\Jobs\UpdateAllAdsPriceJob;
use App\Mail\AdPriceChangedNotification;
use App\Mail\ConfirmSubscriberNotification;
use App\Models\Subscriber;
use App\Models\Subscription;
use Tests\Services\OlxFakeService;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Tests\Traits\HelperTrait;


class SubscriptionTest extends TestCase
{
    use HelperTrait;

    /**
     * A basic feature test example.
     */
    public function test_create_subscription_new_user(): void
    {
        Mail::fake();

        $email = fake()->email;

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html',
        ])->assertJsonStructure([
            'message',
        ]);

        Mail::assertSent(ConfirmSubscriberNotification::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    public function test_verify_subscriber()
    {
        Mail::fake();

        $email = fake()->email;

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html',
        ])->assertJsonStructure([
            'message',
        ]);

        Mail::assertSent(ConfirmSubscriberNotification::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });

        $subscriber = Subscriber::query()->where('email', $email)->first();

        $this->get(route('subscriptions.confirm', [
            'token' => $subscriber->confirmation_token,
        ]))->assertStatus(200);

        $subscriber->refresh();

        $this->assertTrue($subscriber->confirmed);
    }

    public function test_update_ad_price()
    {
        app()->bind(
            AdRequestServiceInterface::class,
            OlxFakeService::class
        );

        Mail::fake();

        $email = fake()->email;
        $url = 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html';

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => $url,
        ])->assertJsonStructure([
            'message',
        ]);

        $subscriber = Subscriber::query()->where('email', $email)->first();

        $this->get(route('subscriptions.confirm', [
            'token' => $subscriber->confirmation_token,
        ]))->assertStatus(200);

        $subscription = Subscription::where('ad_url', $url)->first();

        $job = new UpdateAdPriceJob($subscription);
        $job->handle();

        $subscription->refresh();

        $this->assertNotEquals(0, $subscription->last_price);

        $this->assertTrue($subscription->last_price == $this->generateFakePriceFromUrl($url));
    }

    public function test_update_ad_price_notify()
    {
        Mail::fake();

        app()->bind(
            AdRequestServiceInterface::class,
            OlxFakeService::class
        );

        $email = fake()->email;
        $url = 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html';

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => $url,
        ])->assertJsonStructure([
            'message',
        ]);

        $subscriber = Subscriber::query()->where('email', $email)->first();

        $this->get(route('subscriptions.confirm', [
            'token' => $subscriber->confirmation_token,
        ]))->assertStatus(200);

        $subscription = Subscription::where('ad_url', $url)->first();

        $subscription->update([
            'last_price' => 0,
        ]);

        $job = new UpdateAdPriceJob($subscription);
        $job->handle();

        $subscription->refresh();

        $this->assertNotEquals(0, $subscription->last_price);

        $this->assertTrue($subscription->last_price == $this->generateFakePriceFromUrl($url));

        Mail::assertSent(AdPriceChangedNotification::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    public function test_update_all_ad_price()
    {
        app()->bind(
            AdRequestServiceInterface::class,
            OlxFakeService::class
        );

        $email = fake()->email;
        $url = 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html';

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => $url,
        ])->assertJsonStructure([
            'message',
        ]);

        $subscriber = Subscriber::query()->where('email', $email)->first();

        $this->get(route('subscriptions.confirm', [
            'token' => $subscriber->confirmation_token,
        ]))->assertStatus(200);

        $subscription = Subscription::where('ad_url', $url)->first();

        $job = new UpdateAllAdsPriceJob();
        $job->handle();

        $subscription->refresh();

        $this->assertNotEquals(0, $subscription->last_price);

        $this->assertTrue($subscription->last_price == $this->generateFakePriceFromUrl($url));
    }
}
