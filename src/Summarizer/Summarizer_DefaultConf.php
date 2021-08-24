<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Text\TextInterface;

class Summarizer_DefaultConf implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_DefaultConfInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?Summarizer_DefaultConf {

    $decorated = Summarizer::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula->getDefaultConf());
  }

  /**
   * @param \Donquixote\ObCK\Summarizer\SummarizerInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(SummarizerInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    if (NULL === $conf) {
      $conf = $this->defaultConf;
    }

    return $this->decorated->confGetSummary($conf);
  }

}
