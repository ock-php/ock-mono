<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Translator;

use Donquixote\ObCK\Markup\MarkupInterface;
use Donquixote\ObCK\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\ObCK\Translator\Lookup\TranslatorLookupInterface;
use Donquixote\ObCK\Util\HtmlUtil;

class Translator implements TranslatorInterface {

  /**
   * @var \Donquixote\ObCK\Translator\Lookup\TranslatorLookupInterface
   */
  private $lookup;

  /**
   * @return self
   */
  public static function createPassthru(): self {
    return new self(new TranslatorLookup_Passthru());
  }

  /**
   * @param \Donquixote\ObCK\Translator\Lookup\TranslatorLookupInterface|null $lookup
   *
   * @return self
   */
  public static function create(TranslatorLookupInterface $lookup = NULL): self {

    if (NULL === $lookup) {
      $lookup = new TranslatorLookup_Passthru();
    }

    return new self($lookup);
  }

  /**
   * @param \Donquixote\ObCK\Translator\Lookup\TranslatorLookupInterface $lookup
   */
  public function __construct(TranslatorLookupInterface $lookup) {
    $this->lookup = $lookup;
  }

  /**
   * {@inheritdoc}
   */
  public function translate(string $string, array $replacements = []): string {

    $string = $this->lookup->lookup($string);
    $replacements = $this->processReplacements($replacements);

    return strtr($string, $replacements);
  }

  /**
   * @param string[] $replacements
   *
   * @return string[]
   */
  protected function processReplacements(array $replacements): array {

    // Transform arguments before inserting them.
    foreach ($replacements as $key => $value) {

      if ($value instanceof MarkupInterface) {
        $replacements[$key] = $value->__toString();
      }
      elseif (\is_string($value)) {
        switch ($key[0]) {
          case '!':
            // Unfiltered.
          case '@':
          default:
            // Escaped only.
            $replacements[$key] = HtmlUtil::sanitize($value);
        }
      }
      else {
        $replacements[$key] = '';
      }
    }

    return $replacements;
  }
}
