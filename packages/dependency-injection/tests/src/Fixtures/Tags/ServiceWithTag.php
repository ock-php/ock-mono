<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\Tags;

use Ock\DependencyInjection\Attribute\Service;
use Ock\DependencyInjection\Attribute\ServiceTag;

#[ServiceTag('sunny')]
#[Service]
class ServiceWithTag {

}
