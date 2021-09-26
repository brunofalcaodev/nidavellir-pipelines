<?php

namespace Nidavellir\Pipelines\ProcessAlert;

use Closure;
use Nidavellir\Cube\Models\Api;
use Nidavellir\Pipelines\Instruction;
use Nidavellir\Trading\Logicators\InstructionsLogicator;

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
         * action: buy, sell
         * amount: 4500, max 4500, min 4500, 25%, max 25%, min 25%
         * order: market, plus 1%, minus 1%
         * token: {{ticker}}
         * panic: true, false.
         */
        InstructionsLogicator::validate($data->instructions);

        dd('okay');

        return $next($data);
    }
}
