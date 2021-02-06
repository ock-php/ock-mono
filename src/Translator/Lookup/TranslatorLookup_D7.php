<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Translator\Lookup;

class TranslatorLookup_D7 implements TranslatorLookupInterface {

  /**
   * @return \Donquixote\OCUI\Translator\Lookup\TranslatorLookupInterface
   */
  public static function createOrPassthru(): TranslatorLookupInterface {

    if (NULL !== $lookup = self::create()) {
      return $lookup;
    }

    return new TranslatorLookup_Passthru();
  }

  /**
   * @return self|null
   */
  public static function create(): ?TranslatorLookup_D7 {

    if (0
      || !\function_exists('t')
      // Check some other functions as evidence for Drupal 7.
      || !\function_exists('drupal_placeholder')
      || !\function_exists('module_exists')
    ) {
      return NULL;
    }

    return new self();
  }

  /**
   * Access-restricted constructor, to make sure this is only created in Drupal
   * context.
   */
  protected function __construct() {}

  /**
   * {@inheritdoc}
   */
  public function lookup(string $string): string {
    // Calling t() without any replacements does exactly what we need.
    // The function is only present in a Drupal 7 environment.
    /** @noinspection PhpUndefinedFunctionInspection */
    return (string) \t($string, [], []);
  }
}
