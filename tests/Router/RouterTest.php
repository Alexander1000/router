<?php

namespace Tests\Router;

use Router\Router;
use Router\Exception\NotFound;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    const PATTERN_FIELD = 'uri';

    /**
     * Проверка построение объектов запроса
     *
     * @dataProvider providerRoutes
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     */
    public function testResolve_MethodRouteParams_Request(string $method, string $uri, array $params)
    {
        $router = new Router();

        $request = $router->setBasePath(__DIR__ . '/../../share')
            ->setPatternField(self::PATTERN_FIELD)
            ->setSchema('site')
            ->setMethod($method)
            ->setUri($uri)
            ->resolve();

        foreach ($params as $key => $value) {
            $this->assertNotNull($request->{$key});
            $this->assertEquals($value, $request->{$key});
        }
    }

    /**
     * @return array
     */
    public function providerRoutes()
    {
        return [
            'rootRoute' => [
                'get',
                '/',
                [
                    'param1' => 'test',
                    'param2' => 'test2'
                ]
            ],
            'subRouteRoot' => [
                'get',
                '/test',
                [
                    'uri' => '/',
                    'action' => 'default',
                    'controller' => 'AdminController'
                ]
            ],
            'subRoute' => [
                'get',
                '/test/v2/kkk',
                [
                    'uri' => '/v2/kkk',
                    'action' => 'kk',
                    'controller' => 'AdminController'
                ]
            ],
            'subRouteWithTwoAllowedMethods' => [
                'get',
                '/test/v3/dk',
                [
                    'uri' => '/v3/dk',
                    'action' => 'dk',
                    'controller' => 'AdminController'
                ]
            ],
            'subRouteWithDigitPath' => [
                'get',
                '/sub/34',
                [
                    'uri' => '/34'
                ]
            ],
            'subSubRouteRoot' => [
                'get',
                '/sub/sun',
                [
                    'uri' => '/',
                    'action' => 'default',
                    'controller' => 'AdminController'
                ]
            ],
            'subSubRoute' => [
                'get',
                '/sub/sun/v2/kkk',
                [
                    'uri' => '/v2/kkk',
                    'action' => 'kk',
                    'controller' => 'AdminController'
                ]
            ],
            'subSubRoutePath' => [
                'get',
                '/sub/sun/v3/dk',
                [
                    'uri' => '/v3/dk',
                    'action' => 'dk',
                    'controller' => 'AdminController'
                ]
            ],
            'subSubRouteWithParameters' => [
                'get',
                '/sub/sun/v3/dk?test=1',
                [
                    'uri' => '/v3/dk',
                    'action' => 'dk',
                    'controller' => 'AdminController'
                ]
            ],
            'routeWithPlaceholders' => [
                'put',
                '/test/user/123/edit',
                [
                    'action' => 'updateUser',
                    'controller' => 'UserController',
                    'params' => [
                        'userId' => 123
                    ]
                ]
            ],
            'routeByMethod' => [
                'put',
                '/shop/shop-name',
                [
                    'action' => 'default',
                    'controller' => 'AdminController',
                    'params' => [
                        'shopName' => 'shop-name'
                    ]
                ]
            ],
            'subRouteWithMethodAccess' => [
                'put',
                '/shop/shop-name/user/934/edit',
                [
                    'action' => 'updateUser',
                    'controller' => 'UserController',
                    'params' => [
                        'shopName' => 'shop-name',
                        'userId' => 934
                    ]
                ]
            ],
            'postRouteRoot' => [
                'post',
                '/',
                [
                    'param1' => 'test',
                    'param2' => 'test2'
                ]
            ],
        ];
    }

    /**
     * @dataProvider providerBadUrls
     * @expectedException \Router\Exception\NotFound
     *
     * @param string $method
     * @param string $uri
     */
    public function testResolve_MethodWithUrl_NotFound(string $method, string $uri)
    {
        (new Router())
            ->setBasePath(__DIR__ . '/../../share')
            ->setPatternField(self::PATTERN_FIELD)
            ->setSchema('site')
            ->setMethod($method)
            ->setUri($uri)
            ->resolve();
    }

    /**
     * @return array
     */
    public function providerBadUrls()
    {
        return [
            'subSchemaNotFoundUri' => [
                'get',
                '/test/v4/ts'
            ],
            'notExistsBaseInSubRoute' => [
                'get',
                '/sub',
            ],
            'notExistsInSubSubRoute' => [
                'get',
                '/sub/sun/v4/ts',
            ],
            'fakeRoute' => [
                'get',
                '/jj',
            ],
            'methodNotMatch' => [
                'get',
                '/shop/shop-name/user/934/edit'
            ],
        ];
    }
}
