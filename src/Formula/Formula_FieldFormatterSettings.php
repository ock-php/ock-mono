<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Summarizer\SummarizerInterface;
use Donquixote\Ock\Text\TextInterface;
use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\DrupalText;
use Drupal\ock\Formator\FormatorD8Interface;

/**
 * Formula for the settings for a given formatter.
 */
class Formula_FieldFormatterSettings implements FormatorD8Interface, SummarizerInterface, FormulaInterface {

  /**
   * Formatter with default settings.
   *
   * @var \Drupal\Core\Field\FormatterInterface
   */
  private $stubFormatter;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Field\FormatterInterface $formatter
   *   Formatter with default settings.
   */
  public function __construct(FormatterInterface $formatter) {
    $this->stubFormatter = $formatter;
  }

  /**
   * @param mixed $conf
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   *
   * @return array
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    $formatter = $this->getFormatterWithSettings($conf);

    $form = [
      '#type' => 'container',
      '#input' => TRUE,
      '#tree' => TRUE,
    ];

    $form['#process'][] = static function (array $element, FormStateInterface $formState, array $form) use ($formatter) {
      $element['inner'] = $formatter->settingsForm($form, $formState);
      $element['inner']['#parents'] = $element['#parents'];
      return $element;
    };

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $summary = $this->getFormatterWithSettings($conf)->settingsSummary();

    return DrupalText::fromVarRecursiveUl($summary);
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
    return $this->getFormatterWithSettings($conf)->getSettings();
  }

  /**
   * @param mixed $settings
   *
   * @return \Drupal\Core\Field\FormatterInterface
   */
  private function getFormatterWithSettings($settings = NULL): FormatterInterface {
    $formatter = clone $this->stubFormatter;
    return $formatter->setSettings($settings ?: []);
  }
}
