<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\Tags;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('sunny')]
interface AutoTaggingInterface {

}
