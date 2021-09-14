<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\DecoUtil;

class Generator_DecoKey implements GeneratorInterface {

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private GeneratorInterface $decorated;

  /**
   * @var \Donquixote\Ock\Generator\GeneratorInterface
   */
  private GeneratorInterface $decorator;

  /**
   * @var string
   */
  private string $key;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromDecoKeyFormula(
    Formula_DecoKeyInterface $formula,
    IncarnatorInterface $incarnator
  ): ?self {
    return new self(
      Generator::fromFormula(
        $formula->getDecorated(),
        $incarnator),
      Generator::fromFormula(
        $formula->getDecoratorFormula(),
        $incarnator),
      $formula->getDecoKey());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorated
   * @param \Donquixote\Ock\Generator\GeneratorInterface $decorator
   * @param string $key
   */
  protected function __construct(
    GeneratorInterface $decorated,
    GeneratorInterface $decorator,
    string $key
  ) {
    $this->decorated = $decorated;
    $this->decorator = $decorator;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    $php = $this->decorated->confGetPhp($conf);
    if (is_array($conf)) {
      foreach ($conf[$this->key] ?? [] as $decorator_conf) {
        $decorator_php = $this->decorator->confGetPhp($decorator_conf);
        // @todo Make sure this works reliably!
        $php = str_replace(
          DecoUtil::PLACEHOLDER,
          $php,
          $decorator_php);
      }
    }
    return $php;
  }

}
