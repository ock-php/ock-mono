<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Optional\Formula_OptionalInterface;

class Generator_Optional implements GeneratorInterface {

  /**
   * @param \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    #[Adaptee] Formula_OptionalInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?GeneratorInterface {

    $decorated = Generator::fromFormula($formula->getDecorated(), $universalAdapter);

    if (NULL === $decorated) {
      return NULL;
    }

    return new self(
      $decorated,
      $formula);
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface $formula
   */
  public function __construct(
    private GeneratorInterface $decorated,
    private Formula_OptionalInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->formula->getEmptyPhp();
    }

    $subConf = $conf['options'] ?? NULL;

    return $this->decorated->confGetPhp($subConf);
  }

}
