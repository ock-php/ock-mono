<?php

declare(strict_types=1);

namespace Donquixote\Ock\AnnotatedFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class AnnotatedFormula implements AnnotatedFormulaInterface {

  private string $type;

  private array $info;

  private FormulaInterface $formula;

  /**
   * Constructor.
   *
   * @param string $type
   *   Type name(s), typically a single interface name.
   * @param array $info
   *   Data from annotation(s).
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   */
  public function __construct(string $type, array $info, FormulaInterface $formula) {
    $this->type = $type;
    $this->info = $info;
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * {@inheritdoc}
   */
  public function getInfo(): array {
    return $this->info;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
