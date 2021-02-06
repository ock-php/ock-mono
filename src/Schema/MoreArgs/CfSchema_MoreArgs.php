<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\MoreArgs;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

class CfSchema_MoreArgs extends CfSchema_DecoratorBase implements CfSchema_MoreArgsInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Optionless\CfSchema_OptionlessInterface[]
   */
  private $more;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param string|int $specialKey
   *
   * @return \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgs
   */
  public function createEmpty(CfSchemaInterface $decorated, $specialKey = 0): CfSchema_MoreArgs {
    return new self($decorated, [], $specialKey);
  }

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Schema\Optionless\CfSchema_OptionlessInterface[] $more
   * @param string|int $specialKey
   */
  public function __construct(CfSchemaInterface $decorated, array $more = [], $specialKey = 0) {
    parent::__construct($decorated);
    $this->more = $more;
    $this->specialKey = $specialKey;
  }

  /**
   * @param string $key
   * @param \Donquixote\OCUI\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgs
   */
  public function withItemSchema(string $key, CfSchema_OptionlessInterface $schema): CfSchema_MoreArgs {
    $clone = clone $this;
    $clone->more[$key] = $schema;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getMoreArgs(): array {
    return $this->more;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecialKey(): string {
    return $this->specialKey;
  }
}
