<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translator;

use Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface;

class TranslatorDecoratorBase implements TranslatorInterface {

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(TranslatorInterface $translator) {
    $this->translator = $translator;
  }

  /**
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return static
   */
  public function withTranslator(TranslatorInterface $translator) {
    $clone = clone $this;
    $clone->translator = $translator;
    return $clone;
  }

  /**
   * @param \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface $lookup
   *
   * @return static
   */
  public function withTranslatorLookup(TranslatorLookupInterface $lookup) {
    $clone = clone $this;
    $clone->translator = new Translator($lookup);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function translate(string $string, array $replacements = []): string {
    return $this->translator->translate($string, $replacements);
  }

}
