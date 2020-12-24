<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Crawler
 *
 * @author Bram Raaijmakers
 *
 * @package App\Facades
 */
class Crawler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crawler';
    }
}
