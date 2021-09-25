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
 * (mandatory) $data->instructions: Alert passed instructions (array)
 */
class BodyToInstructions
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        // Parse instructions into an associate laravel collection.
        $instructions = collect(preg_split("/\r\n|\n|\r/", $data->body))->map(function ($item, $key) {
            $values = explode(':', trim(str_replace('  ', ' ', $item)));

            return [strtolower($values[0]) => strtolower($values[1])];
        })->collapse()->toArray();

        if (count($instructions) != 5) {
            throw new AlertException('Incorrect number of instructions!', ['headers' => $data->headers, 'body' => $data->body]);
        }

        data_set(
            $data,
            'instructions',
            $instructions
        );

        return $next($data);
    }
}
