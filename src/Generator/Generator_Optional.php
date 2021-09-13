<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\Optional\Formula_OptionalInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Generator_Optional implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface
   */
  private $formula;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Optional\Formula_OptionalInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_OptionalInterface $formula, IncarnatorInterface $formulaToAnything): ?GeneratorInterface {

    $decorated = Generator::fromFormula($formula->getDecorated(), $formulaToAnything);

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
  public function __construct(GeneratorInterface $decorated, Formula_OptionalInterface $formula) {
    $this->decorated = $decorated;
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (!\is_array($conf) || empty($conf['enabled'])) {
      return $this->formula->getEmptyPhp();
    }

    $subConf = $conf['options'] ?? null;

    return $this->decorated->confGetPhp($subConf);
  }
}
