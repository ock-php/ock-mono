<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\DecoUtil;

class Generator_DecoKey implements GeneratorInterface {

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private GeneratorInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Generator\GeneratorInterface
   */
  private GeneratorInterface $decorator;

  /**
   * @var string
   */
  private string $key;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromDecoKeyFormula(
    Formula_DecoKeyInterface $formula,
    NurseryInterface $formulaToAnything
  ): ?self {
    return new self(
      Generator::fromFormula(
        $formula->getDecorated(),
        $formulaToAnything),
      Generator::fromFormula(
        $formula->getDecoratorFormula(),
        $formulaToAnything),
      $formula->getDecoKey());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorated
   * @param \Donquixote\ObCK\Generator\GeneratorInterface $decorator
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
