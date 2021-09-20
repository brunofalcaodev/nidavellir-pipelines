<?php

namespace Nidavellir\Pipelines\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Exceptions\AlertException;

/**
 * Parses the request headers and body into collections for further use.
 *
 * Needs:
 * (mandatory) $data->body: The request body from the alert post
 * request (string)
 * (mandatory) $data->headers: The request headers from the alert post
 * request (array)
 *
 * Adds:
 * (mandatory) $data->instructions: Alert passed instructions (collection)
 */
class ParseAlertBody
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        if (empty($data->body)) {
            throw new AlertException('Empty instructions, please check alert content!', $data->headers);
        }

        if (count($data->headers) == 0) {
            throw new AlertException('Empty request headers, possible security issue!', $data->headers, $data->body);
        }

        // Parse instructions into an associate laravel collection.
        $instructions = collect(preg_split("/\r\n|\n|\r/", $data->body))->map(function ($item, $key) {
            $values = explode(':', trim(str_replace('  ', ' ', $item)));

            return [strtolower($values[0]) => strtolower($values[1])];
        })->collapse();

        if ($instructions->count() != 5) {
            throw new AlertException('Incorrect number of instructions!', $data->headers, $data->body);
        }

        data_set(
            $data,
            'instructions',
            $instructions
        );

        return $next($data);
    }
}
