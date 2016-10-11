<?php

namespace Router;

class Request implements IRequest
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
