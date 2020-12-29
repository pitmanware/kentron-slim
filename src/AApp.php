<?php

namespace Kentron;

use Slim\App as SlimApp;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

use Kentron\Entity\TransportEntity;
use Kentron\Template\Http\AConfig;

/**
 * The inital application class, injected into the controllers
 */
abstract class AApp extends SlimApp
{
    /**
     * Load up the Slim, Capsule and local systems
     */
    public function __construct (AConfig $config)
    {
        $config::load();

        parent::__construct($this->getSlimContainer());

        $this->bootOrm();
        $this->setVariables();

        if (!$this->allowedAccess()) {
            $this->systemShutdown();
        }

        $this->bootRouter();
    }

    public function init (): void
    {
        $this->run();
    }

    /**
     * Abstract Methods
     */

    /**
     * Set up the database connection
     *
     * @return void
     */
    abstract protected function bootOrm (): void;

    /**
     * Sets the variables in the Variable Store
     *
     * @return void
     */
    abstract protected function setVariables (): void;

    /**
     * Checks if system access is granted based on the app config
     *
     * @return boolean
     */
    abstract protected function allowedAccess (): bool;

    /**
     * Load in all the routes
     *
     * @return void
     */
    abstract protected function bootRouter (): void;

    /**
     * Protected Methods
     */

    /**
     * Shuts down the system with a 403 response code
     *
     * @return void
     */
    protected function systemShutdown (): void
    {
        http_response_code(403);
        die("System access has been disabled");
    }

    /**
     * Returns a dynamic controller closure for Slim to use
     *
     * @param string $controllerClass The FQDN for a controller class
     * @param string $method          The method to call on the controller
     *
     * @return callable The closure
     */
    final protected function getController (string $controllerClass, string $method): callable
    {
        $transportEntity = $this->getContainer()->get("transport_entity");

        return function (Request $request, Response $response, array $args) use ($transportEntity, $controllerClass, $method)
        {
            $transportEntity->setRequest($request);
            $transportEntity->setResponse($response);
            $transportEntity->setArgs($args);

            $controller = new $controllerClass($transportEntity);

            if (!is_subclass_of($controller, AController::class)) {
                throw new \InvalidArgumentException("$controllerClass must be an instance of " . AController::class);
            }

            if (!method_exists($controller, $method) || !is_callable([$controller, $method])) {
                throw new \Error("Call to undefined method {$controllerClass}::{$method}");
            }

            if ($transportEntity->getStatusCode() === 200) {
                $controller->$method();
            }

            return $transportEntity->getResponse();
        };
    }

    /**
     * Gets a middleware class and returns the closure for Slim to use
     *
     * @param string $middlewareClass The FQDN of the middleware class
     *
     * @return callable The closure
     */
    final protected function getMiddleware (string $middlewareClass): callable
    {
        $transportEntity = $this->getContainer()->get("transport_entity");
        $middleware = new $middlewareClass();

        if (!is_subclass_of($middleware, AMiddleware::class)) {
            throw new \InvalidArgumentException("$middlewareClass must be an instance of " . AMiddleware::class);
        }

        return function (Request $request, Response $response, object $next) use ($transportEntity, $middleware)
        {
            $transportEntity->setRequest($request);
            $transportEntity->setResponse($response);
            $transportEntity->setNext($next);

            $middleware->run($transportEntity);

            return $transportEntity->getResponse();
        };
    }

    /**
     * Private Methods
     */

    /**
     * Get the Slim container with some custom handlers
     *
     * @return Container
     */
    private function getSlimContainer (): Container
    {
        $transportEntity = new TransportEntity();
        $container = new Container($this->getSlimConfig());

        $container["notFoundHandler"] = function ($container)
        {
            return function ($request, $response) use ($container)
            {
                if ($request->getMethod() === "GET") {
                    return (new \Slim\Handlers\NotFound)($request, $response);
                }

                return $container["response"]
                    ->withStatus(404)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode([
                        "failed" => true,
                        "data" => null,
                        "errors" => ["invalid route"]
                    ]));

            };

        };

        $container["transport_entity"] = fn () => $transportEntity;

        return $container;
    }
}
