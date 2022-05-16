<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\Ock\Text\TextInterface;

class Summarizer_DefaultConf implements SummarizerInterface {

  /**
   * @var \Donquixote\Ock\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_DefaultConfInterface $formula,
    UniversalAdapterInterface $universalAdapter
  ): ?Summarizer_DefaultConf {

    $decorated = Summarizer::fromFormula(
      $formula->getDecorated(),
      $universalAdapter);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula->getDefaultConf());
  }

  /**
   * @param \Donquixote\Ock\Summarizer\SummarizerInterface $decorated
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
