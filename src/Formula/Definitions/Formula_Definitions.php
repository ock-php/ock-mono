<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Definitions;

use Donquixote\ObCK\Context\CfContextInterface;

class Formula_Definitions implements Formula_DefinitionsInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   */
  public function __construct(array $definitions, CfContextInterface $context = NULL) {
    $this->definitions = $definitions;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions(): array {
    return $this->definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }
}
