<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\ValueProvider;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;

class CfSchema_ValueProvider_FixedValue implements CfSchema_ValueProviderInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @var null|string
   */
  private $php;

  /**
   * @param mixed $value
   * @param string|null $php
   */
  public function __construct($value, $php = NULL) {
    $this->value = $value;
    $this->php = $php;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhp(): string {

    if (NULL !== $this->php) {
      return $this->php;
    }

    $helper = new CodegenHelper();
    return $helper->export($this->value);
  }
}
