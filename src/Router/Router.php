<?php

namespace Router;

class Router implements IRouter
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var string
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

    /**
     * @var string
     */
    protected $patternField = 'uri';

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri ? $uri : '/';
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchema(string $schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPatternField(string $patternField)
    {
        $this->patternField = $patternField;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        $this->uri = preg_replace('/(.*?)\?.*/si', '$1', $this->uri);

        foreach ($this->getRoutes() as $routeInfo) {
            $regexp = sprintf('#%s#si', $routeInfo[$this->patternField]);

            if (preg_match($regexp, $this->uri, $matches)) {
                if (isset($routeInfo['route'])) {
                    return $this->getRouterFromInfo($routeInfo)
                        ->setUri(preg_replace($regexp, '', $this->uri))
                        ->resolve();
                } elseif (strlen($this->uri) == strlen($matches[0])) {
                    return new Request($routeInfo);
                }
            }
        }

        throw new Exception\NotFound();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        if (empty($this->routes)) {
            $this->routes = yaml_parse_file(sprintf('%s/%s.yml', $this->basePath, $this->schema));
        }

        return $this->routes;
    }

    /**
     * Получаем вложенный IRouter
     *
     * @param array $routeInfo
     *
     * @return IRouter
     */
    protected function getRouterFromInfo(array $routeInfo)
    {
        if (isset($routeInfo['class'])) {
            $class = new $routeInfo['class']();
        } else {
            $class = new static();
        }

        $subRouteParts = explode('/', $routeInfo['route']);
        $schemaName = array_pop($subRouteParts);

        /** @var IRouter $class */
        if (empty($subRouteParts)) {
            $class->setBasePath($this->basePath);
        } else {
            $class->setBasePath($this->basePath . '/' . implode('/', $subRouteParts));
        }

        $class->setSchema($schemaName);

        return $class;
    }
}
