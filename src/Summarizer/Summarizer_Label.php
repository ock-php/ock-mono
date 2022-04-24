<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Label\Formula_LabelInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
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
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   */
  #[Adapter]
  public static function create(Formula_LabelInterface $formula, UniversalAdapterInterface $universalAdapter): ?Summarizer_Label {

    try {
      $decorated = Summarizer::fromFormula(
        $formula->getDecorated(),
        $universalAdapter);
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
