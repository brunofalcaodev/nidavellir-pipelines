<?php

namespace Nidavellir\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Exceptions\AlertException;

/**
 * Validates body content.
 *
 * Needs:
 * (mandatory) $data->vody: The request body from the alert post
 * request (string)
 *
 * Adds:
 * Nothing.
 */
class ValidateBody
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        if (empty($data->body)) {
            throw new AlertException('Empty body content, please check alert content!', $data->headers);
        }

        return $next($data);
    }
}
