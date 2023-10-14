<?php

namespace LaravelLiberu\DataImport\Exceptions;

use LaravelLiberu\Helpers\Exceptions\LiberuException;

class Route extends LiberuException
{
    public static function notFound(string $route)
    {
        return new static(__('route does not exist: ":route"', ['route' => $route]));
    }
}
