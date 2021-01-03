<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Schema\StringVal\CfSchema_StringValInterface;
use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\String\V2V_String_Trivial;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;

class Generator_Textfield implements GeneratorInterface {

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
  public static function createFromStringSchema(CfSchema_TextfieldInterface $schema): Generator_Textfield {
    return new self($schema, new V2V_String_Trivial());
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\StringVal\CfSchema_StringValInterface $schema
   *
   * @return self
   */
  public static function createFromStringValSchema(CfSchema_StringValInterface $schema): Generator_Textfield {
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
  public function confGetPhp($conf): string {

    if (!\is_string($conf)) {
      return PhpUtil::incompatibleConfiguration("Value must be a string");
    }

    if ([] !== $errors = $this->schema->textGetValidationErrors($conf)) {
      // @todo Produce a comment from the errors text!
      return PhpUtil::incompatibleConfiguration(count($errors) . ' errors.');
    }

    return $this->v2v->stringGetPhp($conf);
  }
}
