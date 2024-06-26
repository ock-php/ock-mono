<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\FixedConf;

use Ock\Ock\Core\Formula\FormulaInterface;

class Formula_FixedConf implements Formula_FixedConfInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Core\Formula\FormulaInterface $decorated
   * @param mixed $conf
   */
  public function __construct(
    private readonly FormulaInterface $decorated,
    private readonly mixed $conf,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getConf(): mixed {
    return $this->conf;
  }

}
