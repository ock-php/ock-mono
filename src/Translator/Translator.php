<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translator;

use Donquixote\Cf\Markup\MarkupInterface;
use Donquixote\Cf\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface;
use Donquixote\Cf\Util\HtmlUtil;

class Translator implements TranslatorInterface {

  /**
   * @var \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface
   */
  private $lookup;

  /**
   * @return self
   */
  public static function createPassthru(): Translator {
    return new self(new TranslatorLookup_Passthru());
  }

  /**
   * @param \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface|null $lookup
   *
   * @return self
   */
  public static function create(TranslatorLookupInterface $lookup = NULL): Translator {

    if (NULL === $lookup) {
      $lookup = new TranslatorLookup_Passthru();
    }

    return new self($lookup);
  }

  /**
   * @param \Donquixote\Cf\Translator\Lookup\TranslatorLookupInterface $lookup
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
