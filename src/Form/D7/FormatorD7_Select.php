<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Form\D7\Util\D7SelectUtil;
use Donquixote\Cf\Schema\Select\CfSchema_Select_FromFlatSelect;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface;
use Donquixote\Cf\Util\ConfUtil;

class FormatorD7_Select implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface
   */
  private $schema;

  /**
   * @var bool
   */
  private $required = TRUE;


  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface $schema
   *
   * @return self
   */
  public static function createFlat(CfSchema_FlatSelectInterface $schema): FormatorD7_Select {
    return new self(
      new CfSchema_Select_FromFlatSelect($schema));
  }

  /**
   * @STA
   *
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $schema
   *
   * @return self
   */
  public static function create(CfSchema_SelectInterface $schema): FormatorD7_Select {
    return new self($schema);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD7Interface {

    if (!$this->required) {
      return NULL;
    }

    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * @param \Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface $schema
   */
  public function __construct(CfSchemaBase_AbstractSelectInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD7Form($conf, ?string $label): array {

    return D7SelectUtil::optionsSchemaBuildSelectElement(
      $this->schema,
      ConfUtil::confGetId($conf),
      $label,
      $this->required
    );
  }
}
