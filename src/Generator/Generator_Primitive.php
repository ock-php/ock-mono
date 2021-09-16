<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\Ock\Util\MessageUtil;

class Generator_Primitive implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface $formula
   *
   * @return self
   */
  public static function fromFormula(Formula_PrimitiveInterface $formula): Generator_Primitive {
    return new self($formula);
  }

  /**
   * @param \Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface $formula
   */
  public function __construct(Formula_PrimitiveInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    $type = gettype($conf);
    if (!in_array($type, $this->formula->getAllowedTypes())) {
      throw new GeneratorException_IncompatibleConfiguration(
        sprintf(
          'Expected %s, but found %s.',
          implode('|', $this->formula->getAllowedTypes()),
          MessageUtil::formatValue($conf)));
    }
    return var_export($conf, TRUE);
  }

}
