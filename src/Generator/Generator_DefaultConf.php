<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

/**
 * @see \Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConfInterface
 */
class Generator_DefaultConf implements GeneratorInterface {

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
    #[Adaptee] Formula_DefaultConfInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter
  ): ?self {

    $decorated = Generator::fromFormula(
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
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(
    private GeneratorInterface $decorated,
    private $defaultConf,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($conf ?? $this->defaultConf);
  }

}
