<?php
declare(strict_types=1);

namespace Kentron\Template\Http\Factory;

use Kentron\Entity\SlimTransportEntity;

use Kentron\Template\Store\SlimAppStore;

trait TSlimGetTransportEntity
{
    protected static function getTransportEntity(): SlimTransportEntity
    {
        return SlimAppStore::getTransportEntity();
    }
}
