<?php
declare(strict_types=1);

namespace Kentron\Template\Http\Controller;

use Kentron\Entity\SlimTransportEntity;
use Kentron\Entity\TransportEntity;

abstract class ASlimController extends AController
{
    protected TransportEntity|SlimTransportEntity $transportEntity;
}
