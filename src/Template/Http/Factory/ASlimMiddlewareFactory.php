<?php
declare(strict_types=1);

namespace Kentron\Template\Http\Factory;

use Kentron\Entity\TransportEntity;

use Kentron\Template\Store\SlimAppStore;

abstract class ASlimMiddlewareFactory extends AMiddlewareFactory
{
    protected static function getTransportEntity(): TransportEntity
    {
        return SlimAppStore::getTransportEntity();
    }
}
