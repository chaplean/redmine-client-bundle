<?php

namespace Chaplean\Bundle\RedmineClientBundle\Tests\Api;

use Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * RedmineApiTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RedmineApiTest extends TestCase
{
    /**
     * @var RedmineApi
     */
    private $api;

    /**
     * @var array
     */
    private $routes;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->api = new RedmineApi(new Client(), new EventDispatcher(), 'url');
        $reflector = new \ReflectionClass(RedmineApi::class);
        $property = $reflector->getProperty('routes');
        $property->setAccessible(true);
        $this->routes = $property->getValue($this->api);
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $api = new RedmineApi(new Client(), new EventDispatcher(), 'url', 'token');

        $class = new \ReflectionClass(RedmineApi::class);
        $token = $class->getProperty('token');
        $token->setAccessible(true);

        $class = new \ReflectionClass(RedmineApi::class);
        $url = $class->getProperty('url');
        $url->setAccessible(true);

        $this->assertEquals('token', $token->getValue($api));
        $this->assertEquals('url', $url->getValue($api));
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::setToken()
     *
     * @return void
     */
    public function testSetToken()
    {
        $api = new RedmineApi(new Client(), new EventDispatcher(), 'url', 'token');

        $class = new \ReflectionClass(RedmineApi::class);
        $property = $class->getProperty('token');
        $property->setAccessible(true);

        $this->assertEquals('token', $property->getValue($api));

        $api->setToken('le token');
        $this->assertEquals('le token', $property->getValue($api));
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::buildApi()
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $methods = ['projects', 'project', 'users', 'user', 'issues', 'issue', 'times', 'time'];

        $this->assertArrayHasKey('get', $this->routes);
        $this->assertCount(8, $this->routes['get']);
        foreach ($methods as $method) {
            $this->assertArrayHasKey($method, $this->routes['get']);
        }
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::buildApi()
     *
     * @return void
     */
    public function testPostRoutes()
    {
        $methods = ['projects', 'users', 'issues', 'times'];

        $this->assertArrayHasKey('post', $this->routes);
        $this->assertCount(4, $this->routes['post']);
        foreach ($methods as $method) {
            $this->assertArrayHasKey($method, $this->routes['post']);
        }
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::buildApi()
     *
     * @return void
     */
    public function testPutRoutes()
    {
        $methods = ['projects', 'users', 'issues', 'times'];

        $this->assertArrayHasKey('put', $this->routes);
        $this->assertCount(4, $this->routes['put']);
        foreach ($methods as $method) {
            $this->assertArrayHasKey($method, $this->routes['put']);
        }
    }

    /**
     * @covers \Chaplean\Bundle\RedmineClientBundle\Api\RedmineApi::buildApi()
     *
     * @return void
     */
    public function testDeleteRoutes()
    {
        $methods = ['users', 'projects', 'issues', 'times'];

        $this->assertArrayHasKey('delete', $this->routes);
        $this->assertCount(4, $this->routes['delete']);
        foreach ($methods as $method) {
            $this->assertArrayHasKey($method, $this->routes['delete']);
        }
    }
}
