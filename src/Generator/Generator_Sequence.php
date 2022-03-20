<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;
use Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\Ock\Formula\SequenceVal\Formula_SequenceValInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\Ock\V2V\Sequence\V2V_Sequence_Trivial;
use Donquixote\Ock\V2V\Sequence\V2V_SequenceInterface;

class Generator_Sequence implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private $itemGenerator;

  /**
   * @var \Donquixote\Ock\V2V\Sequence\V2V_SequenceInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function createFromSequenceFormula(Formula_SequenceInterface $formula, IncarnatorInterface $incarnator): ?Generator_Sequence {
    return self::create($formula, new V2V_Sequence_Trivial(), $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Formula\SequenceVal\Formula_SequenceValInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function createFromSequenceValFormula(Formula_SequenceValInterface $formula, IncarnatorInterface $incarnator): ?Generator_Sequence {
    return self::create($formula->getDecorated(), $formula->getV2V(), $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Formula\Sequence\Formula_SequenceInterface $formula
   * @param \Donquixote\Ock\V2V\Sequence\V2V_SequenceInterface $v2v
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  private static function create(Formula_SequenceInterface $formula, V2V_SequenceInterface $v2v, IncarnatorInterface $incarnator): ?Generator_Sequence {

    $itemGenerator = Generator::fromFormula(
      $formula->getItemFormula(),
      $incarnator
    );

    if (NULL === $itemGenerator) {
      return NULL;
    }

    return new self($itemGenerator, $v2v);
  }

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $itemGenerator
   * @param \Donquixote\Ock\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  protected function __construct(GeneratorInterface $itemGenerator, V2V_SequenceInterface $v2v) {
    $this->itemGenerator = $itemGenerator;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {

    if (NULL === $conf) {
      $conf = [];
    }
    elseif (!\is_array($conf)) {
      throw new GeneratorException_IncompatibleConfiguration(
        sprintf(
          'Expected an array or NULL, but found %s.',
          MessageUtil::formatValue($conf)));
    }
    elseif ($conf) {
      $keys = array_keys($conf);
      if ($keys !== array_keys($keys)) {
        throw new GeneratorException_IncompatibleConfiguration(
          sprintf(
            'Expected an array with purely sequential keys, found %s.',
            MessageUtil::formatValue($conf)));
      }
    }

    $phpStatements = [];
    foreach ($conf as $itemConf) {
      $phpStatements[] = $this->itemGenerator->confGetPhp($itemConf);
    }

    return $this->v2v->itemsPhpGetPhp($phpStatements);
  }

}
