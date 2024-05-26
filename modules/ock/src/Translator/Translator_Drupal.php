<?php

declare(strict_types=1);

namespace Drupal\ock\Translator;

use Drupal\Core\StringTranslation\TranslationInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\Ock\Translator\TranslatorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[Service]
#[AsAlias(public: true)]
class Translator_Drupal implements TranslatorInterface {

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
  public function __construct(
    #[GetService('string_translation')]
    private readonly TranslationInterface $translation,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function translate(string $source, array $replacements = []): string {
    return (string) $this->translation->translate($source, $replacements);
  }

}
