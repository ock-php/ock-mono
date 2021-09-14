<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\StringVal\Formula_StringValInterface;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\V2V\String\V2V_String_Trivial;
use Donquixote\Ock\V2V\String\V2V_StringInterface;

class Generator_Textfield implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface
   */
  private $formula;

  /**
   * @var \Donquixote\Ock\V2V\String\V2V_StringInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   *
   * @return self
   */
  public static function createFromStringFormula(Formula_TextfieldInterface $formula): Generator_Textfield {
    return new self($formula, new V2V_String_Trivial());
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\StringVal\Formula_StringValInterface $formula
   *
   * @return self
   */
  public static function createFromStringValFormula(Formula_StringValInterface $formula): Generator_Textfield {
    return new self($formula->getDecorated(), $formula->getV2V());
  }

  /**
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   * @param \Donquixote\Ock\V2V\String\V2V_StringInterface $v2v
   */
  public function __construct(Formula_TextfieldInterface $formula, V2V_StringInterface $v2v) {
    $this->formula = $formula;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_string($conf)) {
      return PhpUtil::expectedConfigButFound("Value must be a string", $conf);
    }

    if ([] !== $errors = $this->formula->textGetValidationErrors($conf)) {
      // @todo Produce a comment from the errors text!
      return PhpUtil::expectedConfigButFound(count($errors) . ' errors in text component.', $conf);
    }

    return $this->v2v->stringGetPhp($conf);
  }

}
