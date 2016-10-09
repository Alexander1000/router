<?php

namespace Router;

class Router implements IRouter
{
    /**
     * @var string
     */
    protected $routePath;

    /**
     * @var array
     */
    protected $routes;

    /**
     * @var
     */
    protected $uri;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param string $routePath
     * @return $this
     */
    public function setRoutePath(string $routePath)
    {
        $this->routePath = $routePath;
        return $this;
    }

    /**
     * @return IRequest
     */
    public function resolve()
    {
        return new Request();
    }
}
