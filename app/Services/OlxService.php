<?php

namespace App\Services;

use App\Interfaces\AdRequestServiceInterface;
use Illuminate\Support\Facades\Http;

final class OlxService implements AdRequestServiceInterface
{
    /**
     * The URL of the ad
     *
     * @var string
     */
    private string $url = "";

    /**
     * Set the URL of the ad
     *
     * @param  string  $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Get the URL of the ad
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get price of the ad
     *
     * @return int
     */
    public function getAdPrice(): int
    {
        if ($url = $this->getUrl()) {

            $response = Http::get($url);
            $html = $response->body();

            $regex = '/<script data-rh="true" type="application\/ld\+json">(.*?)<\/script>/s';

            if (preg_match($regex, $html, $matches)) {

                $jsonData = $matches[1];
                $productData = json_decode($jsonData, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $productData['offers']['price'] ?? 0;
                }
            }
        }
        return 0;
    }
}
