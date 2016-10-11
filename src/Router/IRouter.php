<?php

namespace Router;

interface IRouter
{
    /**
     * Uri запроса для которого ищется подходящий сценарий
     *
     * @param string $uri
     *
     * @return $this
     */
    public function setUri(string $uri);

    /**
     * Схема
     *
     * @param string $schema
     *
     * @return $this
     */
    public function setSchema(string $schema);

    /**
     * Папка в которой ищется схема
     *
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath(string $basePath);

    /**
     * Возвращает подходящий сценарий в виде объекта IRequest
     *
     * @return IRequest
     */
    public function resolve();
}
