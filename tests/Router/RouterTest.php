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
            ->setPatternField('uri')
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
}
