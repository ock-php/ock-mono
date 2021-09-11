<?php

declare(strict_types=1);

namespace Drupal\cu\Translator;

use Donquixote\ObCK\Translator\TranslatorInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Translator_Drupal implements TranslatorInterface {

  /**
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  private TranslationInterface $translation;

  /**
   * Static factory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface|null $container
   *   Drupal service container.
   *
   * @return static
   *   Created instance.
   */
  public static function fromContainer(ContainerInterface $container = NULL): self {
    $container = $container ?? \Drupal::getContainer();
    if (!$container) {
      throw new \RuntimeException('Container is NULL.');
    }
    /** @var \Drupal\Core\StringTranslation\TranslationInterface $translation */
    $translation = $container->get('string_translation');
    return new self($translation);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   String translation service.
   */
  public function __construct(TranslationInterface $translation) {
    $this->translation = $translation;
  }

  /**
   * {@inheritdoc}
   */
  public function translate(string $source, array $replacements = []): string {
    return (string) $this->translation->translate($source, $replacements);
  }

}
