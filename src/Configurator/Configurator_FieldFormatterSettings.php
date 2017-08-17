<?php

namespace Drupal\renderkit8\Configurator;

use Drupal\faktoria\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\faktoria\Configurator\ConfiguratorInterface;
use Drupal\faktoria\SummaryBuilder\SummaryBuilderInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Form\FormStateInterface;

class Configurator_FieldFormatterSettings implements ConfiguratorInterface {

  /**
   * @var \Drupal\Core\Field\FormatterInterface
   */
  private $formatter;

  /**
   * @param \Drupal\Core\Field\FormatterInterface $formatter
   */
  public function __construct(FormatterInterface $formatter) {
    $this->formatter = $formatter;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {

    $formatter = $this->getFormatter($conf);

    $form = [
      '#type' => 'container',
      '#input' => TRUE,
      '#tree' => TRUE,
    ];

    $form['#process'][] = function (array $element, FormStateInterface $formState, array $form) use ($formatter) {
      $element['inner'] = $formatter->settingsForm($form, $formState);
      $element['inner']['#parents'] = $element['#parents'];
      return $element;
    };

    return $form;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\faktoria\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    $summary = $this->getFormatter($conf)->settingsSummary();

    if (!is_array($summary)) {
      return $summary;
    }

    $html = '';
    foreach ($summary as $item) {
      $html = '<li>' . Html::escape($item) . '</li>';
    }

    return '<ul>' . $html . '</ul>';
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->confGetFormatterSettings($conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\faktoria\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    return var_export($this->confGetFormatterSettings($conf), TRUE);
  }

  /**
   * @param mixed $conf
   *
   * @return array
   */
  private function confGetFormatterSettings($conf) {
    return $this->getFormatter($conf)->getSettings();
  }

  /**
   * @param mixed $settings
   *
   * @return \Drupal\Core\Field\FormatterInterface
   */
  private function getFormatter($settings = NULL) {
    $this->formatter->setSettings($settings ?: []);
    return $this->formatter;
  }
}
