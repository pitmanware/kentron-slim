<?php

namespace Kentron\Entity;

use Nyholm\Psr7\ServerRequest;
use Slim\Routing\RouteContext;

final class SlimTransportEntity extends TransportEntity
{
    /**
     * Slim route context
     *
     * @var RouteContext
     */
    protected $route;

    /**
     * The name of the route
     *
     * @var string|null
     */
    protected $routeName;

    /**
     * Getters
     */

    public function getRouteName (): ?string
    {
        return $this->routeName;
    }

    /**
     * Setters
     */

    public function setRequest (ServerRequest &$request): void
    {
        parent::{__FUNCTION__}($request);

        $this->route = RouteContext::fromRequest($this->request)->getRoute();
        $this->routeName = $this->route->getName() ?? null;
    }
}
