<?php

namespace Tests\Router;

use Router\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testRouter()
    {
        $router = new Router();

        $router->setBasePath(__DIR__ . '/../../share')
            ->setSchema('site')
            ->setUri('/');

        $router->resolve();
    }
}
