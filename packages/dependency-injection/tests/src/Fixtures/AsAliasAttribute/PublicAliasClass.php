<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\AsAliasAttribute;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(public: true)]
class PublicAliasClass implements PublicAliasInterface {

}
