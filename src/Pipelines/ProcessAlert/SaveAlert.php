<?php

namespace Nidavellir\Pipelines\Pipelines\ProcessAlert;

use Closure;

/**
 * Check if the token canonical exists.
 *
 * Needs:
 * (mandatory) $data->body: An array of alert instructions.
 * (mandatory) $data->headers: An array of request headers.
 *
 * Adds:
 * nothing.
 */
class SaveAlert
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        return $next($data);
    }
}
