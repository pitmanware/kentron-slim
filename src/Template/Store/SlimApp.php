<?php
declare(strict_types=1);

namespace Kentron\Template\Store;

use Kentron\Entity\SlimTransportEntity;
use Kentron\Entity\TransportEntity;

class SlimApp extends App
{
    public static function getTransportEntity(): TransportEntity|SlimTransportEntity
    {
        return parent::getTransportEntity();
    }
}
