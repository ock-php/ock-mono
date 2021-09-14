<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\Ock\Util\PhpUtil;

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
      return PhpUtil::expectedConfigButFound(
        sprintf(
          'Incompatible type: Expected %s',
          implode('|', $this->formula->getAllowedTypes()),
        ),
        $conf);
    }
    return var_export($conf, TRUE);
  }

}
