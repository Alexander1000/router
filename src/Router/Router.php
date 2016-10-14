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

    protected $vars = [];

    protected $placeholders;

    protected $replacement = [
        'integer' => '\d+',
        'int' => '\d+',
        'string' => '[\w\d_-]+'
    ];

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
     * @param string $uri
     * @return string
     */
    protected function filterPlaceholders(string $uri)
    {
        $this->placeholders = [];

        return preg_replace_callback(
            '#\{([\w\d]+):([\w\d]+)\}#si',
            function ($matches) {
                if (isset($this->replacement[$matches[2]])) {
                    $this->placeholders[] = $matches[1];
                    return sprintf('(%s)', $this->replacement[$matches[2]]);
                }

                return $matches[0];
            },
            $uri
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        $this->uri = preg_replace('/(.*?)\?.*/si', '$1', $this->uri);

        foreach ($this->getRoutes() as $routeInfo) {
            $pattern = $this->filterPlaceholders($routeInfo[$this->patternField]);
            $regexp = sprintf('#%s#si', $pattern);

            if (preg_match($regexp, $this->uri, $matches)) {
                if (isset($routeInfo['route'])) {
                    array_shift($matches);

                    return $this->getRouterFromInfo($routeInfo)
                        ->setUri(preg_replace($regexp, '', $this->uri))
                        ->setVars(
                            array_merge(
                                $this->vars,
                                array_combine($this->placeholders, $matches)
                            )
                        )
                        ->resolve();
                } elseif (strlen($this->uri) == strlen($matches[0])) {
                    array_shift($matches);
                    $this->setVars(array_merge($this->vars, array_combine($this->placeholders, $matches)));
                    return new Request($routeInfo + ['params' => $this->vars]);
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
