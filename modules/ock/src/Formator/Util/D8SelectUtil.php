<?php
declare(strict_types=1);

namespace Drupal\ock\Formator\Util;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Formula\Select\Grouped\Formula_GroupedSelectInterface;
use Ock\Ock\Translator\TranslatorInterface;
use Ock\Ock\Util\UtilBase;

final class D8SelectUtil extends UtilBase {

  /**
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   * @param string|null $value
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   * @param bool $required
   *
   * @return array
   *   Drupal form element array.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function selectElementFromCommonSelectFormula(
    Formula_SelectInterface $formula,
    TranslatorInterface $translator,
    ?string $value,
    MarkupInterface|string|null $label,
    bool $required = TRUE
  ): array {
    $options = self::selectOptionsFromCommonSelectFormula($formula, $translator);
    return self::selectElementFromOptions($options, $value, $label, $required);
  }

  /**
   * @param \Ock\Ock\Formula\Select\Grouped\Formula_GroupedSelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   * @param string|null $value
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function selectElementFromGroupedSelectFormula(
    Formula_GroupedSelectInterface $formula,
    TranslatorInterface $translator,
    ?string $value,
    string $label,
    bool $required = TRUE
  ): array {
    $options = self::selectOptionsFromGroupedSelectFormula($formula, $translator);
    return self::selectElementFromOptions($options, $value, $label, $required);
  }

  /**
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   * @param string|null $value
   * @param string $label
   * @param bool $required
   *
   * @return array
   *   Select form element array.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   *   Something went wrong in the formula.
   */
  public static function selectElementFromDrupalSelectFormula(
    Formula_DrupalSelectInterface $formula,
    ?string $value,
    MarkupInterface|string|null $label,
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
   * @param \Drupal\Component\Render\MarkupInterface|string|null $label
   * @param bool $required
   *
   * @return array
   */
  public static function selectElementFromOptions(
    array $options,
    ?string $value,
    MarkupInterface|string|null $label,
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
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   *
   * @return string[][]|string[]
   *   Options to be used in '#options' in a '#type' => 'select' element.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function selectOptionsFromCommonSelectFormula(
    Formula_SelectInterface $formula,
    TranslatorInterface $translator
  ): array {
    $optionsByGroupId = [];
    foreach ($formula->getOptionsMap() as $id => $groupId) {
      $optionsByGroupId[$groupId][$id] = $formula->idGetLabel($id)->convert($translator);
    }
    $options = $optionsByGroupId[''] ?? [];
    unset($optionsByGroupId['']);
    asort($options);
    $groupLabels = [];
    foreach ($optionsByGroupId as $groupId => $optionsInGroup) {
      $groupLabels[$groupId] = $formula->groupIdGetLabel($groupId)
        ?->convert($translator)
        ?? '';
    }
    asort($groupLabels);
    foreach ($groupLabels as $groupId => $groupLabelStr) {
      // Prevent collisions.
      while (isset($options[$groupLabelStr])) {
        $groupLabelStr .= ' ';
      }
      $options[$groupLabelStr] = $optionsByGroupId[$groupId];
      asort($options[$groupLabelStr]);
    }
    return $options;
  }

  /**
   * Gets select options in a format suitable for Drupal.
   *
   * @param \Ock\Ock\Formula\Select\Grouped\Formula_GroupedSelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   *
   * @return (string|string[])[]
   */
  public static function selectOptionsFromGroupedSelectFormula(
    Formula_GroupedSelectInterface $formula,
    TranslatorInterface $translator
  ): array {
    $toplevel_options = [];
    $grouped_options = [];
    foreach ($formula->getOptGroups() as $optgroup) {
      $group_label_obj = $optgroup->getLabel();
      if ($group_label_obj === NULL) {
        foreach ($optgroup->getOptions() as $id => $option) {
          $toplevel_options[$id] = $option->convert($translator);
        }
      }
      else {
        $group_label = $group_label_obj->convert($translator);
        foreach ($optgroup->getOptions() as $id => $option) {
          $grouped_options[$group_label][$id] = $option->convert($translator);
        }
      }
    }
    $options = $toplevel_options;
    foreach ($grouped_options as $group_label => $options_in_group) {
      while (isset($toplevel_options[$group_label])) {
        $group_label .= ' ';
      }
      $options[$group_label] = $options_in_group;
    }
    return $options;
  }

  /**
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   *
   * @return array<string, string|MarkupInterface|array<string, string|MarkupInterface>>
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public static function selectOptionsFromDrupalSelectFormula(Formula_DrupalSelectInterface $formula): array {
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
  private static function idExistsInSelectOptions(string $id, array $options): bool {

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
