<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\ObCK\Util\PhpUtil;

class Generator_Primitive implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface $formula
   *
   * @return self
   */
  public static function fromFormula(Formula_PrimitiveInterface $formula): Generator_Primitive {
    return new self($formula);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Primitive\Formula_PrimitiveInterface $formula
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
