<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterDefinitionList;

use Ock\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Ock\Adaptism\AdaptismPackage;
use Ock\Adaptism\Exception\MalformedAdapterDeclarationException;
use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Ock\ClassDiscovery\Exception\DiscoveryException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Target;

/**
 * The annotated service is an empty definition list.
 */
#[AsAlias(public: true)]
class AdapterDefinitionList_Discovery implements AdapterDefinitionListInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\FactsIA\FactsIAInterface $factsIA
   */
  public function __construct(
    #[Target(AdaptismPackage::DISCOVERY_TARGET)]
    private readonly FactsIAInterface $factsIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    try {
      $definitions = [];
      foreach ($this->factsIA->getIterator() as $delta => $candidate) {
        if (!$candidate instanceof AdapterDefinitionInterface) {
          continue;
        }
        if (isset($definitions[$delta])) {
          for ($i = 0; isset($definitions[$delta . '.' . $i]); ++$i);
          $delta .= '.' . $i;
        }
        $definitions[$delta] = $candidate;
      }
      return $definitions;
    }
    catch (DiscoveryException $e) {
      throw new MalformedAdapterDeclarationException($e->getMessage(), 0, $e);
    }
  }

}
