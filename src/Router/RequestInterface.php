<?php declare(strict_types = 1);

namespace Router;

interface RequestInterface
{
    /**
     * @return null|string
     */
    public function getUri(): ?string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @param array $args
     * @return $this
     */
    public function setArgs(array $args);

    /**
     * @param string $name
     * @return null
     */
    public function getArg(string $name);

    /**
     * @return bool
     */
    public function isPost(): bool;

    /**
     * @return bool
     */
    public function isGet(): bool;

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getGet(string $paramName, $defaultValue = null);

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getPost(string $paramName, $defaultValue = null);

    /**
     * @param string $paramName
     * @param mixed|null $defaultValue
     * @return mixed|null
     * @throws \InvalidArgumentException
     */
    public function getParam(string $paramName, $defaultValue = null);

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getParams(): array;
}
