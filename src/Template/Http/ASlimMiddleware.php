<?php
declare(strict_types=1);

namespace Kentron\Template\Http;

use Kentron\Entity\SlimTransportEntity;
use Kentron\Entity\TransportEntity;

abstract class ASlimMiddleware extends AMiddleware
{
    protected TransportEntity|SlimTransportEntity $transportEntity;
}
