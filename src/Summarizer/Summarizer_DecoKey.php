<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

class Summarizer_DecoKey implements SummarizerInterface {

  /**
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_DecoKeyInterface $formula, UniversalAdapterInterface $universalAdapter): ?self {
    return new self(
      Summarizer::fromFormula(
        $formula->getDecorated(),
        $universalAdapter),
      Summarizer::fromFormula(
        $formula->getDecoratorFormula(),
        $universalAdapter),
      $formula->getDecoKey());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $decorated
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $decorator
   * @param string $key
   */
  public function __construct(
    private readonly SummarizerInterface $decorated,
    private readonly SummarizerInterface $decorator,
    private readonly string $key,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {
    $main_summary = $this->decorated->confGetSummary($conf)
      ?? Text::s('?');
    $decorator_summaries = [];
    if (is_array($conf)) {
      foreach ($conf[$this->key] ?? [] as $decorator_conf) {
        $decorator_summaries[] = $this->decorator->confGetSummary($decorator_conf)
          ?? Text::s('?');
      }
    }
    if (!$decorator_summaries) {
      return $main_summary;
    }
    return Text::ul()
      ->add(
        Text::label(
          Text::t('Plugin'),
          $main_summary,
        ),
      )
      ->add(
        (count($decorator_summaries) <= 1)
          ? Text::label(
          Text::t('Decorator'),
          reset($decorator_summaries))
          : Text::label(
          Text::t('Decorators'),
          Text::ul($decorator_summaries),
        ),
      );
  }

}
