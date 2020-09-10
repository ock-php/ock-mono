<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D8;

use Donquixote\Cf\Form\D8\Optionable\OptionableFormatorD8Interface;
use Donquixote\Cf\Form\D8\Util\D8SelectUtil;
use Donquixote\Cf\Schema\Select\CfSchema_Select_FromFlatSelect;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface;
use Donquixote\Cf\Util\ConfUtil;

class FormatorD8_Select implements FormatorD8Interface, OptionableFormatorD8Interface {

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
  public static function createFlat(CfSchema_FlatSelectInterface $schema): FormatorD8_Select {
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
  public static function create(CfSchema_SelectInterface $schema): FormatorD8_Select {
    return new self($schema);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {

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
  public function confGetD8Form($conf, $label): array {

    return D8SelectUtil::optionsSchemaBuildSelectElement(
      $this->schema,
      ConfUtil::confGetId($conf),
      $label,
      $this->required
    );
  }
}
