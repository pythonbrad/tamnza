<?php

declare(strict_types=1);

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testExtends(): void
    {
        require_once(dirname(__FILE__) . '/../../router/conf.php');
        require_once(dirname(__FILE__) . '/../../router/router.php');

        $router = new \Tamnza\Core\Router('a/b/c');

        $routes = array(
            \Tamnza\Core\includes('a', dirname(__FILE__) . '/urls1.php'),
        );

        $router->extends($routes);

        $router->run();

        $this->assertEquals(null, null);
    }

    public function testAdd(): void
    {
        require_once(dirname(__FILE__) . '/../../router/router.php');

        $router = new \Tamnza\Core\Router('/articles/');

        $router->add('/articles', function () {
            return true;
        });

        $router->add('theme', function () {
            return true;
        });

        $router->add('link', function () {
            return true;
        });

        $router->run();

        $this->assertEquals(null, null);
    }

    public function testAddWithComplexURL(): \Tamnza\Core\Router
    {
        require_once(dirname(__FILE__) . '/../../router/router.php');

        $router = new \Tamnza\Core\Router('/en/85/man-5/FDgfd/Fgf');

        $router->add('/<lang:str>/<id>/<type>-<ref:int>/<path:path>', function (string $lang, int $id, string $type, int $ref, string $path) {
            $this->assertEquals($lang, 'en');
            $this->assertEquals($id, 85);
            $this->assertEquals($type, 'man');
            $this->assertEquals($ref, '5');
            $this->assertEquals($path, 'FDgfd/Fgf');
        }, name: 'test');

        $router->run();

        return $router;
    }

    public function testGenerateUrl(): void
    {
        $router = $this->testAddWithComplexURL();

        $this->assertEquals('?url=/en/85/man-5/FDgfd/Fgf', $router->url('test', array('lang' => 'en', 'id' => 85, 'type' => 'man', 'ref' => 5, 'path' => 'FDgfd/Fgf')));
    }
}
