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
     * @return object
     */
    public function load(string $url): object
    {
        return (object) simplexml_load_file($url);
    }
}
