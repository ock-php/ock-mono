<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Formula\Primitive\Formula_PrimitiveInterface;
use Donquixote\OCUI\Util\PhpUtil;

class Generator_Primitive implements GeneratorInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Primitive\Formula_PrimitiveInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Primitive\Formula_PrimitiveInterface $formula
   *
   * @return self
   */
  public static function fromFormula(Formula_PrimitiveInterface $formula): Generator_Primitive {
    return new self($formula);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Primitive\Formula_PrimitiveInterface $formula
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
      // @todo Produce a comment from the errors text!
      return PhpUtil::incompatibleConfiguration(sprintf(
        'Incompatible type: Expected %s, found %s',
        implode('|', $this->formula->getAllowedTypes()),
        $type));
    }
    return var_export($conf, TRUE);
  }

}
