<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

use Donquixote\ObCK\Exception\EvaluatorException;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface;
use Donquixote\ObCK\Util\PhpUtil;

class V2V_Group_EmptyWithValueProvider implements V2V_GroupInterface {

  /**
   * @var \Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface
   */
  private $valueProvider;

  /**
   * @param \Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProviderInterface $valueProvider
   */
  public function __construct(Formula_ValueProviderInterface $valueProvider) {
    $this->valueProvider = $valueProvider;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {

    if ([] !== $itemsPhp) {
      return PhpUtil::exception(
        EvaluatorException::class,
        "Values must be an empty array.");
    }

    return $this->valueProvider->getPhp();
  }
}
