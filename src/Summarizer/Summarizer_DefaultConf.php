<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Text\TextInterface;

class Summarizer_DefaultConf implements SummarizerInterface {

  /**
   * @var \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @param \Donquixote\OCUI\Summarizer\SummarizerInterface $decorated
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
