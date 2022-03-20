<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Label\Formula_LabelInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

/**
 * Decorator that prepends a "<label>: " to a summary.
 */
class Summarizer_Label implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private $label;

  /**
   * @param \Donquixote\Ock\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   */
  #[OckIncarnator]
  public static function create(Formula_LabelInterface $formula, IncarnatorInterface $incarnator): ?Summarizer_Label {

    try {
      $decorated = Summarizer::fromFormula(
        $formula->getDecorated(),
        $incarnator);
    }
    catch (IncarnatorException $e) {
      return NULL;
    }

    return new self($decorated, $formula->getLabel());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\Ock\Text\TextInterface $label
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
