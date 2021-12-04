<?php

namespace App\Helpers;

/**
 * Class Feed
 *
 * @author Bram Raaijmakers
 *
 * @package App\Helpers
 */
class Feed
{
    /**
     * Return the feed as an array.
     *
     * @param string $url
     *
     * @return array
     */
    public function load(string $url): array
    {
        return (array) simplexml_load_file($url);
    }
}
