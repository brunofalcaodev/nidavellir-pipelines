<?php

namespace Nidavellir\Pipelines;

use Nidavellir\Cube\Models\Api;

class Instruction
{
    public static function __callStatic($method, $args)
    {
        return InstructionService::new()->{$method}(...$args);
    }
}

class InstructionService
{
    protected $error = false;

    public function __construct()
    {
        //
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function onError(callable $callable)
    {
        if ($this->error) {
            $this->error = false;

            return $callable();
        }
    }

    public function validate(string $instruction, string $value)
    {
        switch ($instruction) {
            /**
             * The api ($value) should exist in the database, and must be
             * active.
             */
            case 'api':
                // Not empty and exists in the database.
                if (! Api::firstWhere('hashcode', $value)) {
                    $this->error = true;
                }
                break;

            // Accepts 'buy', 'sell', 'panic'.
            case 'action':
                if (collect(['buy', 'sell', 'panic'])->search($value) === false) {
                    $this->error = true;
                }
                break;

            case 'price':
                // Accepts 'market', 'plus 1%', 'minus 1%'.
                if ($value == 'market') {
                    $this->error = false;

                    return;
                }

                $regex = '/^plus|minus ([0-9*]|[0-9]*\.[0-9]+)%$/i';
                if (! preg_match($regex, $value)) {
                    $this->error = true;
                }
                break;

            case 'amount':
                // Accepts 4500, max 4500, min 4500, 25%, max 25%, min 25%.
                if (is_numeric($value)) {
                    $this->error = false;

                    return;
                }
                break;

            case 'token':
                // coin/token+quote, e.g.: adausdt.
                break;
        }

        return $this;
    }

    public function parse(string $instruction, string $value)
    {
    }
}
