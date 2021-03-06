<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Gunnar Beushausen <gunnar@prestophp.com>	
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests\Provider;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Application;
use PrestoPHP\Provider\SerializerServiceProvider;

/**
 * SerializerServiceProvider test cases.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SerializerServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $app = new Application();

        $app->register(new SerializerServiceProvider());

        $this->assertInstanceOf("Symfony\Component\Serializer\Serializer", $app['serializer']);
        $this->assertTrue($app['serializer']->supportsEncoding('xml'));
        $this->assertTrue($app['serializer']->supportsEncoding('json'));
        $this->assertTrue($app['serializer']->supportsDecoding('xml'));
        $this->assertTrue($app['serializer']->supportsDecoding('json'));
    }
}
