<?php

namespace Tamnza\Test\Core;

use PHPUnit\Framework\TestCase;

final class IncludesTest extends TestCase
{
    public function test(): void
    {
        require(dirname(__FILE__) . '/../../router/conf.php');

        $result = \Tamnza\Core\includes('a', dirname(__FILE__) . '/urls1.php');

        $this->assertIsArray($result);

        $this->assertEquals($result[0][0], 'a/b');
    }
}
