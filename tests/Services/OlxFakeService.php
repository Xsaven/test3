<?php

namespace Tests\Services;

use App\Interfaces\AdRequestServiceInterface;
use Illuminate\Support\Facades\Http;

final class OlxFakeService implements AdRequestServiceInterface
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

            return substr(crc32($url), 0, 5);
        }
        return 0;
    }
}
