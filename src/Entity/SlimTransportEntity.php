<?php

namespace Kentron\Entity;

use Psr\Http\Message\ServerRequestInterface;

use Slim\Routing\RouteContext;

final class SlimTransportEntity extends TransportEntity
{
    /** The name of the route */
    protected string|null $routeName = null;

    /** The arguments of the route if there are any */
    protected array $routeArguments = [];

    /**
     * Getters
     */

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getRouteArgument(string $key): int|string|null
    {
        return $this->routeArguments[$key] ?? null;
    }

    /**
     * Setters
     */

    public function setRequest (ServerRequestInterface &$request): void
    {
        parent::setRequest($request);

        $route = RouteContext::fromRequest($this->request)->getRoute();

        $this->routeName = $route->getName() ?? null;
        $this->routeArguments = $route->getArguments();
    }
}
