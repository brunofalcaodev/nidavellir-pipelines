<?php

namespace Nidavellir\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Exceptions\AlertException;

/**
 * Parses the request headers and body into collections for further use.
 *
 * Needs:
 * (mandatory) $data->body: The request body from the alert post
 * request (string)
 *
 * Adds:
 * (mandatory) $data->instructions: Alert passed instructions (collection)
 */
class ParseBody
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
