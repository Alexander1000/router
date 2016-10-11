<?php

namespace Tests\Router;

use Router\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getDataRequest()
    {
        return [
            [
                '/',
                [
                    'param1' => 'test',
                    'param2' => 'test2'
                ]
            ],
            [
                '/test',
                [
                    'uri' => '/',
                    'action' => 'default',
                    'controller' => 'AdminController'
                ]
            ],
            [
                '/test/v2/kkk',
                [
                    'uri' => '/v2/kkk',
                    'action' => 'kk',
                    'controller' => 'AdminController'
                ]
            ],
            [
                '/test/v3/dk',
                [
                    'uri' => '/v3/dk',
                    'action' => 'dk',
                    'controller' => 'AdminController'
                ]
            ]
        ];
    }

    /**
     * Проверка построения маршрутов
     */
    public function testRouter()
    {
        $this->markTestSkipped();

        $router = new Router();

        $router->setBasePath(__DIR__ . '/../../share')
            ->setSchema('site')
            ->setUri('/');

        $router->resolve();
    }

    /**
     * Проверка построение объектов запроса
     *
     * @dataProvider getDataRequest
     *
     * @param string $uri
     * @param array $params
     */
    public function testResolve(string $uri, array $params)
    {
        $router = new Router();

        $router->setBasePath(__DIR__ . '/../../share')
            ->setSchema('site')
            ->setUri($uri);

        $response = $router->resolve();

        foreach ($params as $key => $value) {
            $this->assertEquals($value, $response->{$key});
        }
    }
}
