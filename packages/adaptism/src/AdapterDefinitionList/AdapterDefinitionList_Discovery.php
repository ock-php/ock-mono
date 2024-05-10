<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinitionList;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinitionInterface;
use Donquixote\Adaptism\AdaptismPackage;
use Donquixote\Adaptism\Exception\MalformedAdapterDeclarationException;
use Donquixote\ClassDiscovery\Discovery\DiscoveryInterface;
use Donquixote\DID\Exception\DiscoveryException;
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
   * @param \Donquixote\ClassDiscovery\Discovery\DiscoveryInterface $discovery
   */
  public function __construct(
    #[Target(AdaptismPackage::DISCOVERY_TARGET)]
    private readonly DiscoveryInterface $discovery,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    try {
      $definitions = [];
      foreach ($this->discovery->getIterator() as $delta => $candidate) {
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
