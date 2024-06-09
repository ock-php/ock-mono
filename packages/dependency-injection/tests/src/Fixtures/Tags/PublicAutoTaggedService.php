<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\Tags;

use Ock\DependencyInjection\Attribute\Service;

#[Service]
class PublicAutoTaggedService implements AutoTaggingInterface {

}
