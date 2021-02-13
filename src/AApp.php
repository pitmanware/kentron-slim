<?php

namespace Kentron;

use Slim\App as SlimApp;

use Kentron\Template\{IApp,TApp};

/**
 * The inital application class, injected into the controllers
 */
abstract class AApp extends SlimApp implements IApp
{
    use TApp;
}
