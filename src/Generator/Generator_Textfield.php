<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\StringVal\Formula_StringValInterface;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Translator\Translator;
use Donquixote\Ock\Util\MessageUtil;
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
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $formula
   *
   * @return self
   */
  #[OckIncarnator]
  public static function createFromStringFormula(Formula_TextfieldInterface $formula): Generator_Textfield {
    return new self($formula, new V2V_String_Trivial());
  }

  /**
   * @param \Donquixote\Ock\Formula\StringVal\Formula_StringValInterface $formula
   *
   * @return self
   */
  #[OckIncarnator]
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
      throw new GeneratorException_IncompatibleConfiguration(
        sprintf(
          'Expected a string, found %s.',
          MessageUtil::formatValue($conf)));
    }

    if ([] !== $errors = $this->formula->textGetValidationErrors($conf)) {
      throw new GeneratorException_IncompatibleConfiguration(
        sprintf(
          'Text %s fails validation: %s.',
          MessageUtil::formatValue($conf),
          Text::concat($errors, ', ')->convert(Translator::passthru())));
    }

    return $this->v2v->stringGetPhp($conf);
  }

}
