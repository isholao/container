<?php

namespace Isholao\Container\Tests;

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    public function testShortcut()
    {
        $c = new \Isholao\Container\Container();
        $c->set('name', 'ishola');

        $this->assertSame('ishola', $c->name);

        $this->assertSame('ishola', $c->get('name'));

        $this->assertTrue($c->has('name'));
        $this->assertFalse($c->has('non existance'));


        $c->protect('response',
                    function ()
        {
            return 'response';
        });

        $this->assertSame('response', $c->response());
    }

}
