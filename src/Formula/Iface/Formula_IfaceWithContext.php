<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Iface;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Optional\Formula_Optional;
use Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface;
use Donquixote\OCUI\Formula\Sequence\Formula_Sequence;

class Formula_IfaceWithContext implements Formula_IfaceWithContextInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|NULL
   */
  private $context;

  /**
   * @var string
   */
  private $cacheId;

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Formula\Sequence\Formula_Sequence
   */
  public static function createSequence(string $interface, CfContextInterface $context = NULL): Formula_Sequence {
    return new Formula_Sequence(
      new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\OCUI\Formula\Optional\Formula_OptionalInterface
   */
  public static function createOptional(string $interface, CfContextInterface $context = NULL): Formula_OptionalInterface {
    return new Formula_Optional(new self($interface, $context));
  }

  /**
   * @param $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   * @param bool $required
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function create($interface, CfContextInterface $context = NULL, $required = TRUE): FormulaInterface {
    $formula = new self($interface, $context);
    if (!$required) {
      $formula = new Formula_Optional($formula);
    }
    return $formula;
  }

  /**
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   */
  public function __construct(string $interface, CfContextInterface $context = NULL) {
    $this->interface = $interface;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function getInterface(): string {
    return $this->interface;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheId(): string {
    return $this->cacheId
      ?? $this->cacheId = $this->buildCacheId();
  }

  /**
   * @return string
   */
  private function buildCacheId(): string {

    $id = $this->interface;

    if (NULL !== $this->context) {
      $id .= $this->context->getMachineName();
    }

    return $id;
  }
}
