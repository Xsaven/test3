<?php

namespace App\Interfaces;

interface AdRequestServiceInterface
{
    /**
     * Set the URL of the ad
     *
     * @param  string  $url
     * @return void
     */
    public function setUrl(string $url): void;

    /**
     * Get the URL of the ad
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get price of the ad
     *
     * @return int
     */
    public function getAdPrice(): int;
}
