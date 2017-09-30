<?php declare(strict_types = 1);

namespace Router;

class Request implements RequestInterface
{
    private $get;
    private $post;
    private $cookie;
    private $server;
    private $files;

    private $args;

    private static $instance;

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
     * @return null
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
}
