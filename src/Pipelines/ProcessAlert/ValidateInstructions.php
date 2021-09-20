<?php

namespace Nidavellir\Pipelines\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Cube\Models\Api;
use Nidavellir\Exceptions\AlertException;
use Nidavellir\Pipelines\Instruction;

/**
 * Parses the request headers and body into arrays for further use.
 * Verifies if all the instruction content makes sense and it's valid.
 *
 * Needs:
 * (mandatory) $data->instructions: Instructions to validate (collection).
 *
 * Adds:
 * (optional) $data->error: In case a validation error is triggered.
 */
class ValidateInstructions
{
    public function __construct()
    {
        //
    }

    public function handle($data, Closure $next)
    {
        /**
         * The instructions are defined as:
         * api: <code>
         * action: buy/sell/panic
         * amount: 4500, max 4500, min 4500, 25%, max 25%, min 25%
         * price: market, plus 1%, minus 1%
         * token: {{ticker}}.
         */
        $pass = $data->instructions->has([
            'api',
            'action',
            'amount',
            'price',
            'token',
        ]);

        if (! $pass) {
            throw new AlertException('Missing instructions, please check alert content', $data->headers, $data->body);
        }

        /**
         * "api" instruction.
         * api:<code> where <code> is a valid api hashcode.
         */
        $headers = $data->headers;
        $body = $data->body;

        Instruction::validate('action', $data->instructions['action'])
                   ->onError(function () use ($headers, $body) {
                       throw new AlertException('Unknown action', $headers, $body);
                   });

        Instruction::validate('api', $data->instructions['api'])
                   ->onError(function () use ($headers, $body) {
                       throw new AlertException('Unknown api hashcode', $headers, $body);
                   });

        Instruction::validate('amount', $data->instructions['amount'])
                   ->onError(function () use ($headers, $body) {
                       throw new AlertException('Invalid amount', $headers, $body);
                   });

        //Instruction::validate('action', $data->instructions['action']);
        //Instruction::validate('amount', $data->instructions['amount']);
        //Instruction::validate('price', $data->instructions['price']);
        //Instruction::validate('token', $data->instructions['token']);

        return $next($data);
    }
}
