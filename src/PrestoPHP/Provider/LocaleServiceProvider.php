<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Gunnar Beushausen <gunnar@prestophp.com>	
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PrestoPHP\Api\EventListenerProviderInterface;
use PrestoPHP\Provider\Locale\LocaleListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Locale Provider.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class LocaleServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        $app['locale.listener'] = function ($app) {
            return new LocaleListener($app, $app['locale'], $app['request_stack'], isset($app['request_context']) ? $app['request_context'] : null);
        };

        $app['locale'] = 'en';
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app['locale.listener']);
    }
}
