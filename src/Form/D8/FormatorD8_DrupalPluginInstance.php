<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\faktoria\Formula\Formula_DrupalPluginInstanceInterface;
use Drupal\faktoria\Formula\Formula_DrupalPluginInterface;
use function foo\func;

class FormatorD8_DrupalPluginInstance implements FormatorD8Interface {

  /**
   * @var \Drupal\Core\Plugin\PluginFormInterface
   */
  private $instance;

  public static function fromPluginInstance($instance) {
    if ($instance instanceof PluginFormInterface) {
      if ($instance instanceof ConfigurablePluginInterface) {
        return new self($instance);
      }
    }
  }

  /**
   * @param \Drupal\Core\Plugin\PluginFormInterface $instance
   */
  public function __construct(PluginFormInterface $instance) {
    $this->instance = $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {
    // @todo Get the original form state from somewhere.
    $this->instance->buildConfigurationForm([], new FormState());
    $element = [];
    $element['#tree'] = TRUE;
    $element['#process'][] = function (array $element, FormStateInterface $formState) {
      // @todo Create subform state.
      $element['plugin'] = $this->instance->buildConfigurationForm([], $formState);
      return $element;
    };
    return $element;
  }

}
