<?php

namespace Router;

class Router implements IRouter
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var
     */
    protected $uri;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $schema;

    protected $patternField = 'uri';

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? null;
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
     * @param string $schema
     * @return $this
     */
    public function setSchema(string $schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @return IRequest
     */
    public function resolve()
    {
        foreach ($this->getRoutes() as $routeInfo) {

        }

        return new Request();
    }

    protected function getRoutes()
    {
        if (empty($this->routes)) {
            $this->routes = yaml_parse_file(sprintf('%s/%s.yml', $this->basePath, $this->schema));

            foreach ($this->routes as $routeName => $routeInfo) {
                if (isset($routeInfo['route'])) {
                    if (isset($routeInfo['class'])) {
                        $class = new $routeInfo['class']();
                    } else {
                        $class = new static();
                    }

                    /** @var IRouter $class */
                    $subRouteParts = explode('/', $routeInfo['route']);

                    $schemaName = array_pop($subRouteParts);

                    if (empty($subRouteParts)) {
                        $class->setBasePath($this->basePath);
                    } else {
                        $class->setBasePath($this->basePath . '/' . implode('/', $subRouteParts));
                    }

                    $class->setSchema($schemaName);

                    $routeInfo['routes'] = $class->getRoutes();

                    $this->routes[$routeName] = $routeInfo;
                }
            }
        }

        return $this->routes;
    }
}
