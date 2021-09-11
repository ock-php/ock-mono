<?php
declare(strict_types=1);

namespace Drupal\ock\Formator\Util;

use Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Donquixote\Ock\Util\UtilBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

final class D8SelectUtil extends UtilBase {

  /**
   * @param \Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   * @param string|null $value
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function selectElementFromCommonSelectFormula(
    FormulaBase_AbstractSelectInterface $formula,
    TranslatorInterface $translator,
    ?string $value,
    string $label,
    bool $required = TRUE
  ): array {
    $options = self::selectOptionsFromCommonSelectFormula($formula, $translator);
    return self::selectElementFromOptions($options, $value, $label, $required);
  }

  /**
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   * @param string|null $value
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function selectElementFromDrupalSelectFormula(
    Formula_DrupalSelectInterface $formula,
    ?string $value,
    string $label,
    bool $required = TRUE
  ): array {
    $options = self::selectOptionsFromDrupalSelectFormula($formula);
    return self::selectElementFromOptions($options, $value, $label, $required);
  }

  /**
   * Builds a select element with special behavior.
   *
   * @param array $options
   * @param string|null $value
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function selectElementFromOptions(
    array $options,
    ?string $value,
    string $label,
    bool $required = TRUE
  ): array {

    $element = [
      '#title' => $label,
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $value,
    ];

    if (NULL !== $value
      && !self::idExistsInSelectOptions($value, $element['#options'])
    ) {
      $element['#options'][$value] = t("Unknown id '@id'", ['@id' => $value]);
      $element['#element_validate'][] = static function (array $element, FormStateInterface $form_state) use ($value) {
        if ($value === (string) $element['#value']) {
          $form_state->setError(
            $element,
            t(
              "Unknown id %id. Maybe the id did exist in the past, but it currently does not.",
              ['%id' => $value]
            )
          );
        }
      };
    }

    if ($required) {
      $element['#required'] = TRUE;
    }
    else {
      $element['#empty_value'] = '';
    }

    return $element;
  }

  /**
   * Gets select options in a format suitable for Drupal 8.
   *
   * @param \Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *
   * @return string[][]|string[]
   *   Options to be used in '#options' in a '#type' => 'select' element.
   */
  public static function selectOptionsFromCommonSelectFormula(
    FormulaBase_AbstractSelectInterface $formula,
    TranslatorInterface $translator
  ): array {
    $options = [];
    // Get the top-level options first.
    foreach ($formula->getOptions(NULL) as $id => $option_label_obj) {
      $options[$id] = $option_label_obj->convert($translator);
    }
    // Build the opt groups.
    foreach ($formula->getOptGroups() as $group_id => $group_label_obj) {
      $group_label_str = $group_label_obj->convert($translator);
      while (isset($options[$group_label_str])) {
        $group_label_obj .= ' ';
      }
      foreach ($formula->getOptions($group_id) as $id => $option_label_obj) {
        $options[$group_label_str][$id] = $option_label_obj->convert($translator);
      }
    }
    return $options;
  }

  /**
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   *
   * @return \Drupal\Component\Render\MarkupInterface[]|\Drupal\Component\Render\MarkupInterface[][]|string|string[]|\string[][]
   */
  public static function selectOptionsFromDrupalSelectFormula(Formula_DrupalSelectInterface $formula) {
    // Get rid of empty groups.
    $grouped_options = array_filter($formula->getGroupedOptions());
    $toplevel_options = $grouped_options[''] ?? NULL;
    if ($toplevel_options === NULL) {
      return $grouped_options;
    }
    unset($grouped_options['']);
    $options = $toplevel_options;
    foreach ($grouped_options as $group_label_str => $options_in_group) {
      while (isset($options[$group_label_str])) {
        $group_label_str .= ' ';
      }
      $options[$group_label_str] = $options_in_group;
    }
    return $options;
  }

  /**
   * @param string $id
   * @param string[][]|string[] $options
   *
   * @return bool
   */
  private static function idExistsInSelectOptions($id, array $options): bool {

    if (isset($options[$id]) && !\is_array($options[$id])) {
      return TRUE;
    }

    foreach ($options as $optgroup) {
      if (\is_array($optgroup) && isset($optgroup[$id])) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
