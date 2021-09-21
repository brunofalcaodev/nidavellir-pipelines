<?php

namespace Nidavellir\Pipelines\ProcessAlert;

/**
 * Retrieves a crypto currency price data line.
 */
class ProcessAlert
{
    public function __invoke()
    {
        return [
            ValidateHeaders::class,
            ParseBody::class,
            ValidateInstructions::class,
            SaveAlert::class,
            //CreateOrder::class,
            //UpdateAlert::class
        ];
    }
}
