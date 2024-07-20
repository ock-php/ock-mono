<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Ock\Adaptism\Tests;

use Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Ock\Adaptism\Tests\Fixtures\FixturesUtil;
use Ock\Testing\RecordedTestTrait;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase {

  use RecordedTestTrait;

  public function testDefinitionListAsRecorded(): void {
    /** @var AdapterDefinitionListInterface $adapterDefinitionList */
    $adapterDefinitionList = FixturesUtil::getContainer()
      ->get(AdapterDefinitionListInterface::class);
    $definitions = $adapterDefinitionList->getDefinitions();
    $this->assertAsRecorded($definitions);
  }

}
