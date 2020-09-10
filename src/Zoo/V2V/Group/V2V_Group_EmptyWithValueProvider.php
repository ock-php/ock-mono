<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Group;

use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface;
use Donquixote\Cf\Util\PhpUtil;

class V2V_Group_EmptyWithValueProvider implements V2V_GroupInterface {

  /**
   * @var \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface
   */
  private $valueProvider;

  /**
   * @param \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface $valueProvider
   */
  public function __construct(CfSchema_ValueProviderInterface $valueProvider) {
    $this->valueProvider = $valueProvider;
  }

  /**
   * {@inheritdoc}
   */
  public function valuesGetValue(array $values) {
    if ([] !== $values) {
      throw new EvaluatorException("Values must be an empty array.");
    }

    return $this->valueProvider->getValue();
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
