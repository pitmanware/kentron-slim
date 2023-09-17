<?php
declare(strict_types=1);

namespace Kentron\Template\Http\Router\Adapter;

use Slim\Interfaces\RouteCollectorProxyInterface;

use Kentron\Template\Http\Router\ARouteAdapter;
use Kentron\Template\Http\Router\Route\Group;
use Kentron\Template\Http\Router\Route\Get;
use Kentron\Template\Http\Router\Route\Post;
use Kentron\Template\Http\Router\Route\Put;
use Kentron\Template\Http\Router\Route\Delete;
use Kentron\Template\Http\Router\Route\Options;

final class RouteAdapterSlim extends ARouteAdapter
{
    public function __construct(
        public Group $group,
        private RouteCollectorProxyInterface $parent
    ) {}

    public function translate(): void
    {
        $callable = function (RouteCollectorProxyInterface $router) {
            foreach ($this->group->iterateRoutes() as $route) {
                if ($route instanceof Group) {
                    (new self($route, $router))->translate();
                    continue;
                }

                $slimRoute = match ($route::class) {
                    Get::class     => $router->get($route->path, $route->controller),
                    Post::class    => $router->post($route->path, $route->controller),
                    Put::class     => $router->put($route->path, $route->controller),
                    Delete::class  => $router->delete($route->path, $route->controller),
                    Options::class => $router->options($route->path, $route->controller)
                };

                if (isset($route->name)) {
                    $slimRoute->setName($route->name);
                }

                foreach ($route->middlewares as $middleware) {
                    $slimRoute->add($middleware);
                }
            }
        };

        $slimGroup = $this->parent->group($this->group->path, $callable);

        foreach ($this->group->middlewares as $middleware) {
            $slimGroup->add($middleware);
        }
    }
}
