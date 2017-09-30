<?php declare(strict_types = 1);

namespace Router;

interface RouterInterface
{
    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri);

    /**
     * Возвращает подходящий сценарий в виде объекта RequestInterface
     * @return RequestInterface
     * @throws Exception\NotFound
     */
    public function resolve(): RequestInterface;

    /**
     * Список маршрутов
     * @return array
     */
    public function getRoutes(): array;

    /**
     * Переменные
     * @param array $vars
     * @return self
     */
    public function setVars(array $vars);
}
