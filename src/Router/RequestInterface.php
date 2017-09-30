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
}
