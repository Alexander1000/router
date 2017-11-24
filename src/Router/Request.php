<?php declare(strict_types = 1);

namespace Router;

class Request implements RequestInterface
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';

    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $cookie;

    /**
     * @var array
     */
    private $server;

    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $args;

    protected static $instance;

    public function __construct(array $get, array $post, array $cookie, array $server, array $files)
    {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->server = $server;
        $this->files = $files;
    }

    /**
     * @return RequestInterface
     */
    public static function instance(): RequestInterface
    {
        if (!static::$instance) {
            static::$instance = new static($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES);
        }
        
        return static::$instance;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getArg(string $name)
    {
        return $this->args[$name] ?? null;
    }

    /**
     * @return null|string
     */
    public function getUri(): ?string
    {
        return $this->server['REQUEST_URI'] ?? null;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] == self::METHOD_POST;
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->server['REQUEST_METHOD'] == self::METHOD_GET;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getGet(string $paramName, $defaultValue = null)
    {
        return $this->get[$paramName] ?? $defaultValue;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getPost(string $paramName, $defaultValue = null)
    {
        return $this->post[$paramName] ?? $defaultValue;
    }

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getParam(string $paramName, $defaultValue = null)
    {
        switch ($this->getMethod()) {
            case self::METHOD_GET:
                return $this->getGet($paramName, $defaultValue);
            case self::METHOD_POST:
                return $this->getPost($paramName, $defaultValue);
        }

        throw new \InvalidArgumentException('Method not allowed');
    }
}
