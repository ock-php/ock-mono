<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Group;

use Donquixote\OCUI\Exception\EvaluatorException;
use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface;
use Donquixote\OCUI\Util\PhpUtil;

class V2V_Group_EmptyWithValueProvider implements V2V_GroupInterface {

  /**
   * @var \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface
   */
  private $valueProvider;

  /**
   * @param \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface $valueProvider
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
