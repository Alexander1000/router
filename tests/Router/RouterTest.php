<?php

namespace Tests\Router;

use Router\Router;
use Router\Exception\NotFound;

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
            ],
            [
                '/test/v4/ts',
                []
            ],
            [
                '/sub',
                []
            ],
            [
                '/sub/34',
                [
                    'uri' => '/34'
                ]
            ],
            [
                '/sub/sun',
                [
                    'uri' => '/',
                    'action' => 'default',
                    'controller' => 'AdminController'
                ]
            ],
            [
                '/sub/sun/v2/kkk',
                [
                    'uri' => '/v2/kkk',
                    'action' => 'kk',
                    'controller' => 'AdminController'
                ]
            ],
            [
                '/sub/sun/v3/dk',
                [
                    'uri' => '/v3/dk',
                    'action' => 'dk',
                    'controller' => 'AdminController'
                ]
            ],
            [
                '/sub/sun/v4/ts',
                []
            ],
            [
                '/jj',
                []
            ]
        ];
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

        try {
            $response = $router->resolve();

            if (empty($params)) {
                $this->fail('Exception expected');
            }

            foreach ($params as $key => $value) {
                $this->assertEquals($value, $response->{$key});
            }
        } catch (NotFound $e) {
            if (!empty($params)) {
                $this->fail('Exception not expected');
            }
        }
    }
}
