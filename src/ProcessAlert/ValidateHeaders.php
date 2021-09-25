<?php

namespace Nidavellir\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Exceptions\AlertException;

/**
 * Validates webhook headers.
 *
 * Needs:
 * (mandatory) $data->headers: The request body from the alert post
 * request (array)
 *
 * Adds:
 * Nothing.
 */
class ValidateHeaders
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        if (count($data->headers) == 0 && app()->environment() == 'production') {
            throw new AlertException('Empty request headers, possible security issue!', ['headers' => $data->headers, 'body' => $data->body]);
        }

        return $next($data);
    }
}
