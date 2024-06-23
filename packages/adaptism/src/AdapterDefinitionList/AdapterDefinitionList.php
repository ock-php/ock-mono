<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterDefinitionList;

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\FactsIA\FactsIA;
use Ock\DependencyInjection\Attribute\Service;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class AdapterDefinitionList {

  #[Service]
  public static function fromCandidateObjects(
    #[TaggedIterator(AdaptismPackage::DISCOVERY_TAG_NAME)]
    iterable $candidates,
  ): AdapterDefinitionListInterface {
    $factsIA = FactsIA::fromCandidateObjects($candidates);
    return new AdapterDefinitionList_Discovery($factsIA);
  }

}
