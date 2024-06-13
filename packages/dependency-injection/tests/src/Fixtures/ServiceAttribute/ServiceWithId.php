<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ServiceAttribute;

use Ock\DependencyInjection\Attribute\Service;

/**
 * A simple class that will be registered as private service, then forgotten.
 */
#[Service(serviceId: 'custom_service_id')]
class ServiceWithId {

}
