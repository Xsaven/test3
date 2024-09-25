<?php

namespace Tests\Traits;

trait HelperTrait
{
    public function generateFakePriceFromUrl(string $url): int
    {
        return substr(crc32($url), 0, 5);
    }
}
