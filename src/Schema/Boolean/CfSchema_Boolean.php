<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Boolean;

class CfSchema_Boolean implements CfSchema_BooleanInterface {

  /**
   * @var string|null
   */
  private $trueSummary;

  /**
   * @var string|null
   */
  private $falseSummary;

  /**
   * @param string|null $trueSummary
   * @param string|null $falseSummary
   */
  public function __construct($trueSummary, $falseSummary) {
    $this->trueSummary = $trueSummary;
    $this->falseSummary = $falseSummary;
  }

  /**
   * {@inheritdoc}
   */
  public function getTrueSummary(): ?string {
    return $this->trueSummary;
  }

  /**
   * {@inheritdoc}
   */
  public function getFalseSummary(): ?string {
    return $this->falseSummary;
  }
}
