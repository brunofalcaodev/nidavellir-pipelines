<?php

namespace Nidavellir\Pipelines\Pipelines\ProcessAlert;

/**
 * Retrieves a crypto currency price data line.
 */
class ProcessAlert
{
    public function __invoke()
    {
        return [
            ParseAlertBody::class,
            ValidateInstructions::class,
            SaveAlert::class,
            //CreateOrder::class,
            //UpdateAlert::class
        ];
    }
}
