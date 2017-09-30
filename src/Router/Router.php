<?php declare(strict_types = 1);

namespace Router;

class Router implements RouterInterface
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
     * Переменные из placeholder'ов
     * @var array
     */
    protected $vars = [];

    /**
     * Список placeholder'ов
     * @var string[]
     */
    protected $placeholders;

    /**
     * @var RequestInterface
     */
    protected $request;

    protected $replacement = [
        'integer' => '\d+',
        'int' => '\d+',
        'string' => '[\w\d_-]+'
    ];

    /**
     * @var string
     */
    protected $schemaPath;

    public function __construct(string $basePath, string $schema, RequestInterface $request)
    {
        $this->basePath = $basePath;
        $this->schema = $schema;
        $this->request = $request;
        $this->uri = $request->getUri();
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
     * {@inheritdoc}
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
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
     * Дополнительные проверки маршрута
     * @param array $routeInfo
     * @return bool
     */
    protected function isValid(array $routeInfo): bool
    {
        if (!empty($routeInfo['method'])) {
            return in_array(
                strtoupper($this->request->getMethod()),
                explode(' ', strtoupper($routeInfo['method']))
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(): RequestInterface
    {
        $this->uri = preg_replace('/(.*?)\?.*/si', '$1', $this->uri);

        foreach ($this->getRoutes() as $routeInfo) {
            $pattern = $this->filterPlaceholders($routeInfo['uri']);
            $regexp = sprintf('#%s#si', $pattern);

            if (preg_match($regexp, $this->uri, $matches) && $this->isValid($routeInfo)) {
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
                    return $this->makeRequest($routeInfo + ['params' => $this->vars]);
                }
            }
        }

        throw new Exception\NotFound();
    }

    /**
     * @param array $params
     * @return RequestInterface
     */
    protected function makeRequest(array $params): RequestInterface
    {
        return $this->request->setArgs($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes(): array
    {
        if (empty($this->routes)) {
            $this->routes = yaml_parse_file(sprintf('%s/%s.yml', $this->basePath, $this->schema));
        }

        return $this->routes;
    }

    /**
     * Получаем вложенный RouterInterface
     * @param array $routeInfo
     * @return RouterInterface
     */
    protected function getRouterFromInfo(array $routeInfo): RouterInterface
    {
        $class = $routeInfo['class'] ?? static::class;
        return new $class($this->basePath, $routeInfo['route'], $this->request);
    }
}
