<?php

declare(strict_types=1);

namespace Drupal\renderkit_example\BuildProvider;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\renderkit\BuildProvider\BuildProviderInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Provides a "Hello, world!" build provider.
 */
#[OckPluginInstance('hello', 'Hello')]
class BuildProvider_Hello implements BuildProviderInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#markup' => $this->t('Hello, world!'),
    ];
  }

}
