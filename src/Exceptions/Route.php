<?php

namespace LaravelLiberu\DataImport\Exceptions;

use LaravelLiberu\Helpers\Exceptions\EnsoException;

class Route extends EnsoException
{
    public static function notFound(string $route)
    {
        return new static(__('route does not exist: ":route"', ['route' => $route]));
    }
}
