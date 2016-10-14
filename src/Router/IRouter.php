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
     * Шаблон для поиска по запросу
     *
     * @param string $patternField
     *
     * @return $this
     */
    public function setPatternField(string $patternField);

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
     *
     * @throws Exception\NotFound
     */
    public function resolve();

    /**
     * Список маршрутов
     *
     * @return array
     */
    public function getRoutes();

    /**
     * Переменные
     *
     * @param array $vars
     *
     * @return $this
     */
    public function setVars(array $vars);
}
