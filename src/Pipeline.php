<?php

namespace Nidavellir\Pipelines;

class Pipeline
{
    public static function __callStatic($method, $args)
    {
        return PipelineService::new()->{$method}(...$args);
    }
}

class PipelineService
{
    protected $data = [];
    protected $pipeline = null;

    public function __construct()
    {
        //
    }

    public static function new(...$args)
    {
        return new self(...$args);
    }

    public function get(string $path)
    {
        return data_get($this->data, $path);

        return $this;
    }

    public function set(string $path, $value)
    {
        data_set($this->data, $path, $value);

        return $this;
    }

    public function onPipeline(string $pipeline)
    {
        $this->pipeline = $pipeline;

        return $this;
    }

    public function execute()
    {
        app(\Illuminate\Pipeline\Pipeline::class)
            ->send($this->data())
            ->through((new $this->pipeline())())
            ->thenReturn();
    }

    private function data()
    {
        return (object) $this->data;
    }
}
