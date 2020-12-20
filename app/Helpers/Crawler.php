<?php

namespace App\Helpers;

/**
 * Class Crawler
 *
 * @author Bram Raaijmakers
 *
 * @package App\Helpers
 */
class Crawler
{
    /**
     * Retrieve the status code of the page.
     *
     * @param string $url
     *
     * @return integer
     */
    public function getStatusCode(string $url)
    {
        $headers = @get_headers($url);
        if ($headers && isset($headers[14]) && strpos($headers[14], '200')) {
            return 200;
        }

        return 404;
    }
}
