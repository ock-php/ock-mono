<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Para\Formula_ParaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

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
   * @param \Donquixote\Ock\Formula\Para\Formula_ParaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
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
      // phpcs:ignore
      $paraConf = eval($paraConfPhp);
    }
    catch (\Exception $e) {
      // Use 'if' instead of separate 'catch' to avoid false inspection.
      // See https://youtrack.jetbrains.com/issue/WI-62853.
      if ($e instanceof GeneratorException) {
        // Exception already has the correct type.
        throw $e;
      }
      // Wrong exception type. Convert.
      throw new GeneratorException_IncompatibleConfiguration(
        $e->getMessage(), 0, $e);
    }

    return $this->paraGenerator->confGetPhp($paraConf);
  }

}
