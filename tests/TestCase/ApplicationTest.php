<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase;

use App\Application;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestCase;
use InvalidArgumentException;

/**
 * ApplicationTest class
 */
class ApplicationTest extends IntegrationTestCase
{
    /**
     * testBootstrap
     *
     * @return void
     */
    public function testBootstrap()
    {
        $app = new Application(dirname(__DIR__, 2) . '/config');
        $app->bootstrap();
        $plugins = $app->getPlugins();
        $this->assertCount(5, $plugins);
        $this->assertSame('Bake', $plugins->get('Bake')->getName());
        $this->assertSame('Migrations', $plugins->get('Migrations')->getName());
        $this->assertSame('DebugKit', $plugins->get('DebugKit')->getName());
    }

    /**
     * testBootstrapPluginWitoutHalt
     *
     * @return void
     */
    public function testBootstrapPluginWitoutHalt()
    {
        $this->expectException(InvalidArgumentException::class);
        $app = $this->getMockBuilder(Application::class)
            ->setConstructorArgs([dirname(__DIR__, 2) . '/config'])
            ->setMethods(['addPlugin'])
            ->getMock();
        $app->method('addPlugin')
            ->will($this->throwException(new InvalidArgumentException('test exception.')));
        $app->bootstrap();
    }

    /**
     * testMiddleware
     *
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(__DIR__, 2) . '/config');
        $middleware = new MiddlewareQueue();
        $middleware = $app->middleware($middleware);
        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->current());
        $middleware->next();
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->current());
        $middleware->next();
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->current());
    }
}
