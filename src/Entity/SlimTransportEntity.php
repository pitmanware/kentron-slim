<?php

namespace Kentron\Entity;

use Psr\Http\Message\ServerRequestInterface;

use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;

final class SlimTransportEntity extends TransportEntity
{
    /** The slim route context */
    protected RouteInterface $route;

    /** The name of the route */
    protected string|null $routeName = null;

    /**
     * Getters
     */

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    public function getRouteName (): ?string
    {
        return $this->routeName;
    }

    public function getRouteArgument(string $key): ?string
    {
        return $this->route->getArgument($key);
    }

    public function getRouteArguments(): array
    {
        return $this->route->getArguments();
    }

    /**
     * Setters
     */

    public function setRequest (ServerRequestInterface &$request): void
    {
        parent::setRequest($request);

        $this->route = RouteContext::fromRequest($this->request)->getRoute();
        $this->routeName = $this->route->getName() ?? null;
    }

    public function setRouteName(?string $name = null): void
    {
        $this->routeName = $name;
    }
}
