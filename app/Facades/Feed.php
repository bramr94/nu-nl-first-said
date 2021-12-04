<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Feed
 *
 * @author Bram Raaijmakers
 *
 * @package App\Facades
 */
class Feed extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'feed';
    }
}
