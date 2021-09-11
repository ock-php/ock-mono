<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Ock\Exception\IncarnatorException;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormFactoryInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Plugin\PluginWithFormsInterface;
use Drupal\ock\Formula\DrupalPluginSettings\Formula_DrupalPluginSettingsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FormatorD8_DrupalPluginSettings implements FormatorD8Interface {

  /**
   * @var \Drupal\Core\Plugin\PluginFormInterface
   */
  private PluginFormInterface $pluginForm;

  /**
   * @STA
   *
   * @param \Drupal\ock\Formula\DrupalPluginSettings\Formula_DrupalPluginSettingsInterface $formula
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromFormula(Formula_DrupalPluginSettingsInterface $formula, ContainerInterface $container): FormatorD8Interface {
    /** @var \Drupal\Core\Plugin\PluginFormFactoryInterface $pluginFormFactory */
    $pluginFormFactory = $container->get('plugin_form.factory');
    return self::fromPlugin(
      $formula->getPlugin(),
      $pluginFormFactory);
  }

  /**
   * @param object $plugin
   * @param \Drupal\Core\Plugin\PluginFormFactoryInterface $pluginFormFactory
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromPlugin(object $plugin, PluginFormFactoryInterface $pluginFormFactory): FormatorD8Interface {
    if ($plugin instanceof PluginWithFormsInterface) {
      try {
        $plugin = $pluginFormFactory->createInstance($plugin, 'configure');
      }
      catch (InvalidPluginDefinitionException $e) {
        throw new IncarnatorException($e->getMessage(), 0, $e);
      }
      return new self($plugin);
    }
    if ($plugin instanceof PluginFormInterface) {
      return new self($plugin);
    }
    return new FormatorD8_Optionless();
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Plugin\PluginFormInterface $pluginForm
   */
  public function __construct(PluginFormInterface $pluginForm) {
    $this->pluginForm = $pluginForm;
  }

  public function confGetD8Form($conf, $label): array {
    return [
      '#tree' => TRUE,
      '#process' => function (array $element, FormStateInterface $form_state, $complete_form): array {
        $element['form'] = $this->pluginForm->buildConfigurationForm($complete_form, $form_state);
        $element['form']['#parents'] = $element['#parents'];
        return $element;
      }
    ];
  }

}
