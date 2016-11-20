<?php

namespace Tests\Router;

use Router\Router;
use Router\Exception\NotFound;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    const PATTERN_FIELD = 'uri';

    /**
     * @return array
     */
    public function getDataRequest()
    {
        return require __DIR__ . '/../../share/fixtures/routes.php';
    }

    /**
     * Проверка построение объектов запроса
     *
     * @dataProvider getDataRequest
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     */
    public function testResolve(string $method, string $uri, array $params)
    {
        $router = new Router();

        $router->setBasePath(__DIR__ . '/../../share')
            ->setPatternField(self::PATTERN_FIELD)
            ->setSchema('site')
            ->setMethod($method)
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

    /**
     * @dataProvider providerBadUrls
     * @expectedException \Router\Exception\NotFound
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
