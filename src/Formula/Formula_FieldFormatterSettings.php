<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Evaluator\EvaluatorInterface;
use Donquixote\ObCK\Form\D8\FormatorD8Interface;
use Donquixote\ObCK\Summarizer\SummarizerInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Form\FormStateInterface;

class Formula_FieldFormatterSettings implements FormatorD8Interface, SummarizerInterface, EvaluatorInterface, FormulaInterface {

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
   * @return array
   */
  public function confGetD8Form($conf, $label): array {

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

    if (!\is_array($summary)) {

      if (null === $summary) {
        return null;
      }

      return (string)$summary;
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
  public function confGetPhp($conf): string {
    return var_export($this->confGetFormatterSettings($conf), TRUE);
  }

  /**
   * @param mixed $conf
   *
   * @return array
   */
  private function confGetFormatterSettings($conf): array {
    return $this->getFormatter($conf)->getSettings();
  }

  /**
   * @param mixed $settings
   *
   * @return \Drupal\Core\Field\FormatterInterface
   */
  private function getFormatter($settings = NULL): FormatterInterface {
    $this->formatter->setSettings($settings ?: []);
    return $this->formatter;
  }
}
