<?php

namespace Tests\Feature;

use App\Interfaces\AdRequestServiceInterface;
use App\Jobs\UpdateAdPriceJob;
use App\Models\Subscriber;
use App\Models\Subscription;
use Tests\Services\OlxFakeService;
use Tests\TestCase;
use Tests\Traits\HelperTrait;

class CommandsTest extends TestCase
{
    use HelperTrait;

    public function test_ad_info_command()
    {
        app()->bind(
            AdRequestServiceInterface::class,
            OlxFakeService::class
        );

        $url = 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz.html';
        $this->artisan('ad:info ' . $url)
            ->expectsOutput('Price: ' . $this->generateFakePriceFromUrl($url))
            ->assertExitCode(0);
    }

    public function test_ad_reset_command()
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

        $job = new UpdateAdPriceJob($subscription);
        $job->handle();

        $subscription->refresh();

        $this->assertNotEquals(0, $subscription->last_price);

        $this->assertTrue($subscription->last_price == $this->generateFakePriceFromUrl($url));

        $this->artisan('ad:reset')
            ->expectsOutput('All ads have been reset')
            ->assertExitCode(0);

        $subscription->refresh();

        $this->assertEquals(0, $subscription->last_price);
    }

    public function test_ad_show_command()
    {
        app()->bind(
            AdRequestServiceInterface::class,
            OlxFakeService::class
        );

        $email = fake()->email;
        $url = 'https://www.olx.ua/d/uk/obyavlenie/bluetti-eb3a-600w-IDVagDz1.html';

        $this->postJson(route('subscriptions.subscribe'), [
            'email' => $email,
            'ad_url' => $url,
        ])->assertJsonStructure([
            'message',
        ]);

        $subscriber = Subscriber::query()->where('email', $email)->first();

        $this->assertTrue($subscriber instanceof Subscriber);

        $this->get(route('subscriptions.confirm', [
            'token' => $subscriber->confirmation_token,
        ]))->assertStatus(200);

        $subscription = Subscription::where('ad_url', $url)->first();

        $job = new UpdateAdPriceJob($subscription);
        $job->handle();

        $subscription->refresh();

        $this->assertNotEquals(0, $subscription->last_price);

        $this->artisan('ad:show')

            ->expectsTable(
                ['Ad URL', 'Last Price'],
                [
                    [$url, number_format($this->generateFakePriceFromUrl($url), 2, '.', ' ')],
                ]
            )
            ->assertExitCode(0);
    }
}
