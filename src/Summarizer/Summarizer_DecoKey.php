<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

class Summarizer_DecoKey implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private SummarizerInterface $decorated;

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private SummarizerInterface $decorator;

  private string $key;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function create(Formula_DecoKeyInterface $formula, NurseryInterface $formulaToAnything): ?self {
    return new self(
      Summarizer::fromFormula(
        $formula->getDecorated(),
        $formulaToAnything),
      Summarizer::fromFormula(
        $formula->getDecoratorFormula(),
        $formulaToAnything),
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
    SummarizerInterface $decorated,
    SummarizerInterface $decorator,
    string $key
  ) {
    $this->decorated = $decorated;
    $this->decorator = $decorator;
    $this->key = $key;
  }

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
          $main_summary))
      ->add(
        (count($decorator_summaries) <= 1)
          ? Text::label(
          Text::t('Decorator'),
          reset($decorator_summaries))
          : Text::label(
          Text::t('Decorators'),
          Text::ul($decorator_summaries)));
  }

}
