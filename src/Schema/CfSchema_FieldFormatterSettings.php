<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\Form\D8\FormatorD8Interface;
use Donquixote\Cf\Summarizer\SummarizerInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Form\FormStateInterface;

class CfSchema_FieldFormatterSettings implements FormatorD8Interface, SummarizerInterface, EvaluatorInterface {

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
   * @param string $label
   *
   * @return array|null
   */
  public function confGetD8Form($conf, $label) {

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
   *
   * @return null|string
   */
  public function confGetSummary($conf) {

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
   *
   * @return string
   */
  public function confGetPhp($conf) {
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
