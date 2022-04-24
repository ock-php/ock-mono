<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Para\Formula_ParaInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

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
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_ParaInterface $formula, UniversalAdapterInterface $universalAdapter): Generator_Para {
    return new self(
      Generator::fromFormula($formula->getDecorated(), $universalAdapter),
      Generator::fromFormula($formula->getParaFormula(), $universalAdapter));
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
