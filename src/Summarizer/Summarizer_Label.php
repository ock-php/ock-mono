<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Label\Formula_LabelInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

/**
 * Decorator that prepends a "<label>: " to a summary.
 */
class Summarizer_Label implements SummarizerInterface {

  /**
   * @var \Donquixote\ObCK\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private $label;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self|null
   */
  public static function create(Formula_LabelInterface $formula, NurseryInterface $formulaToAnything): ?Summarizer_Label {

    if (NULL === $decorated = Summarizer::fromFormula(
        $formula->getDecorated(),
        $formulaToAnything
      )
    ) {
      return NULL;
    }

    return new self($decorated, $formula->getLabel());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\ObCK\Text\TextInterface $label
   */
  public function __construct(SummarizerInterface $decorated, TextInterface $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $decorated = $this->decorated->confGetSummary($conf);

    if ('' === $decorated || NULL === $decorated) {
      return $decorated;
    }

    return Text::label(
      $this->label,
      $decorated);
  }
}
