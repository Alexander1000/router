<?php declare(strict_types = 1);

namespace Tests\Router;

use Router\Request;

class RouterMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Request method GET
     */
    public function testIsGet_RequestData_Success()
    {
        $request = new Request(
            [],
            [],
            [],
            [
                'REQUEST_METHOD' => Request::METHOD_GET
            ],
            []
        );

        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isPost());
    }

    /**
     * Request method POST
     */
    public function testIsPost_RequestData_Success()
    {
        $request = new Request(
            [],
            [],
            [],
            [
                'REQUEST_METHOD' => Request::METHOD_POST
            ],
            []
        );

        $this->assertFalse($request->isGet());
        $this->assertTrue($request->isPost());
    }

    /**
     * Request getParam()
     */
    public function testGetGet_Data_Success()
    {
        $request = new Request(
            [
                'var1' => 'val1',
            ],
            [
                'var1' => 'val2',
                'var2' => 'val3',
            ],
            [],
            [
                'REQUEST_METHOD' => Request::METHOD_GET
            ],
            []
        );

        $this->assertEquals('val1', $request->getGet('var1'));
        $this->assertEquals('val2', $request->getPost('var1'));
        $this->assertEquals('val1', $request->getParam('var1'));
        $this->assertEquals('val000', $request->getParam('var2', 'val000'));
        $this->assertEquals('val000', $request->getGet('aaa', 'val000'));
    }

    /**
     * Request getParam()
     */
    public function testGetPost_Data_Success()
    {
        $request = new Request(
            [
                'var1' => 'val1',
            ],
            [
                'var1' => 'val2',
                'var2' => 'val3',
            ],
            [],
            [
                'REQUEST_METHOD' => Request::METHOD_POST
            ],
            []
        );

        $this->assertEquals('val1', $request->getGet('var1'));
        $this->assertEquals('val2', $request->getPost('var1'));
        $this->assertEquals('val2', $request->getParam('var1'));
        $this->assertEquals('val3', $request->getParam('var2', 'val000'));
        $this->assertEquals('val000', $request->getGet('aaa', 'val000'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Method not allowed
     */
    public function testNotExistMethod_Data_InvalidArgumentException()
    {
        $request = new Request(
            [
                'var1' => 'val1',
            ],
            [
                'var1' => 'val2',
                'var2' => 'val3',
            ],
            [],
            [
                'REQUEST_METHOD' => 'INVALID_METHOD'
            ],
            []
        );

        $request->getParam('test');
    }
}
