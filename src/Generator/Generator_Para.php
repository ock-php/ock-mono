<?php
declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\Para\Formula_ParaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\PhpUtil;

class Generator_Para implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $paraGenerator;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Para\Formula_ParaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(Formula_ParaInterface $formula, IncarnatorInterface $incarnator): Generator_Para {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $incarnator),
      Generator::fromFormula($formula->getParaFormula(), $incarnator));
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Ock\Generator\GeneratorInterface $paraGenerator
   */
  public function __construct(GeneratorInterface $decorated, GeneratorInterface $paraGenerator) {
    $this->decorated = $decorated;
    $this->paraGenerator = $paraGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    $paraConfPhp = $this->decorated->confGetPhp($conf);

    try {
      // @todo Use a service that can pass in variables!
      $paraConf = eval($paraConfPhp);
    }
    catch (\Exception $e) {
      return PhpUtil::incompatibleConfiguration($e->getMessage());
    }

    return $this->paraGenerator->confGetPhp($paraConf);
  }
}
