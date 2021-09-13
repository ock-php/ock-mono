<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\FixedConf\Formula_FixedConfInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

class Generator_FixedConf implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\FixedConf\Formula_FixedConfInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_FixedConfInterface $formula, NurseryInterface $formulaToAnything): GeneratorInterface {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $formulaToAnything),
      $formula->getConf());
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param mixed $conf
   */
  public function __construct(GeneratorInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($this->conf);
  }
}
