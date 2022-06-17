<?php

namespace Kentron;

use Slim\App as SlimApp;

use Kentron\Entity\SlimTransportEntity;
use Kentron\Template\IApp;
use Kentron\Template\TApp;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Container\ContainerInterface;

use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\MiddlewareDispatcherInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteResolverInterface;

/**
 * The inital application class, injected into the controllers
 */
abstract class ASlimApp extends SlimApp implements IApp
{
    use TApp;

    /**
     * @param ResponseFactoryInterface           $responseFactory
     * @param ContainerInterface|null            $container
     * @param CallableResolverInterface|null     $callableResolver
     * @param RouteCollectorInterface|null       $routeCollector
     * @param RouteResolverInterface|null        $routeResolver
     * @param MiddlewareDispatcherInterface|null $middlewareDispatcher
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ?ContainerInterface $container = null,
        ?CallableResolverInterface $callableResolver = null,
        ?RouteCollectorInterface $routeCollector = null,
        ?RouteResolverInterface $routeResolver = null,
        ?MiddlewareDispatcherInterface $middlewareDispatcher = null
    ) {
        parent::__construct(
            $responseFactory,
            $container,
            $callableResolver,
            $routeCollector,
            $routeResolver,
            $middlewareDispatcher
        );

        $this->setTransportEntityClass(SlimTransportEntity::class);
    }
}
