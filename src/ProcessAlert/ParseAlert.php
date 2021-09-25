<?php

namespace Nidavellir\Pipelines\ProcessAlert;

/**
 * Retrieves a crypto currency price data line.
 */
class ParseAlert
{
    public function __invoke()
    {
        return [
            ValidateHeaders::class,
            ValidateBody::class,
            BodyToInstructions::class,
            ValidateInstructions::class,
            SaveAlert::class,
            //CreateOrder::class,
            //UpdateAlert::class
        ];
    }
}
