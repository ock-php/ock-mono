<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Ock\Ock\Attribute\Parameter\OckListOfObjects;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\ListFormat\ListFormatInterface;

#[OckPluginInstance('sequence', 'Sequence of build providers')]
class BuildProvider_Sequence implements BuildProviderInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface[] $providers
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $listFormat
   */
  public function __construct(
    #[OckOption('build_providers', 'Build providers')]
    #[OckListOfObjects(BuildProviderInterface::class)]
    private readonly array $providers,
    #[OckOption('list_format', 'List format')]
    private readonly ?ListFormatInterface $listFormat = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $elements = [];
    foreach ($this->providers as $k => $provider) {
      $element = $provider->build();
      if ($element) {
        $elements[$k] = $element;
      }
    }
    if (!$elements) {
      return [];
    }
    return $this->listFormat?->buildList($elements) ?? $elements;
  }
}
