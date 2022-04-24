<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Util\DecoUtil;

class Generator_DecoKey implements GeneratorInterface {

  /**
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromDecoKeyFormula(
    #[Adaptee] Formula_DecoKeyInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter
  ): ?self {
    return new self(
      Generator::fromFormula(
        $formula->getDecorated(),
        $universalAdapter,
      ),
      Generator::fromFormula(
        $formula->getDecoratorFormula(),
        $universalAdapter,
      ),
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
    private readonly GeneratorInterface $decorated,
    private readonly GeneratorInterface $decorator,
    private readonly string $key,
  ) {}

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
