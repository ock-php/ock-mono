<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\StringVal\CfSchema_StringValInterface;
use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\String\V2V_String_Trivial;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;

class Evaluator_Textfield implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface
   */
  private $v2v;

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema
   *
   * @return self
   */
  public static function createFromStringSchema(CfSchema_TextfieldInterface $schema): Evaluator_Textfield {
    return new self($schema, new V2V_String_Trivial());
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\StringVal\CfSchema_StringValInterface $schema
   *
   * @return self
   */
  public static function createFromStringValSchema(CfSchema_StringValInterface $schema): Evaluator_Textfield {
    return new self($schema->getDecorated(), $schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $schema
   * @param \Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface $v2v
   */
  public function __construct(CfSchema_TextfieldInterface $schema, V2V_StringInterface $v2v) {
    $this->schema = $schema;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {

    if (!\is_string($conf)) {
      throw new EvaluatorException_IncompatibleConfiguration("Value must be a string");
    }

    if ([] !== $errors = $this->schema->textGetValidationErrors($conf)) {
      throw new EvaluatorException_IncompatibleConfiguration(reset($errors));
    }

    return $this->v2v->stringGetValue($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_string($conf)) {
      return PhpUtil::incompatibleConfiguration("Value must be a string");
    }

    if ([] !== $errors = $this->schema->textGetValidationErrors($conf)) {
      return PhpUtil::incompatibleConfiguration(reset($errors));
    }

    return $this->v2v->stringGetPhp($conf);
  }
}
