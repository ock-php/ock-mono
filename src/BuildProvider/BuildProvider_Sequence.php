<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Callback\Formula_Callback;
use Drupal\renderkit\ListFormat\ListFormatInterface;

class BuildProvider_Sequence implements BuildProviderInterface {

  /**
   * @var \Drupal\renderkit\BuildProvider\BuildProviderInterface[]
   */
  private $providers;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @CfrPlugin(
   *   id = "sequence",
   *   label = "Sequence of build providers"
   * )
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function getCfrFormula(CfContextInterface $context = NULL): FormulaInterface {

    return Formula_Callback::fromClass(__CLASS__)
      ->withContext($context)
      ->withParam_IfaceSequence(
        0,
        BuildProviderInterface::class,
        t('Build providers'))
      ->withParam_IfaceOrNull(
        1,
        ListFormatInterface::class,
        t('List format'));
  }

  /**
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface[] $providers
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(array $providers, ListFormatInterface $listFormat = NULL) {
    $this->providers = $providers;
    $this->listFormat = $listFormat;
  }

  /**
   * @return array
   *   A render array.
   */
  public function build(): array {
    $builds = [];
    foreach ($this->providers as $k => $provider) {
      $build = $provider->build();
      if (\is_array($build) && [] !== $build) {
        $builds[$k] = $provider->build();
      }
    }
    if ([] === $builds) {
      return [];
    }
    if (NULL !== $this->listFormat) {
      $builds = $this->listFormat->buildList($builds);
    }
    return $builds;
  }
}
