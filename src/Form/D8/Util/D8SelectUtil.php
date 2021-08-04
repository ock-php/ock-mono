<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\D8\Util;

use Donquixote\OCUI\FormulaBase\FormulaBase_AbstractSelectInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;
use Donquixote\OCUI\Util\UtilBase;
use Drupal\Core\Form\FormStateInterface;

final class D8SelectUtil extends UtilBase {

  /**
   * @param \Donquixote\OCUI\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   * @param string|null $id
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function optionsFormulaBuildSelectElement(
    FormulaBase_AbstractSelectInterface $formula,
    TranslatorInterface $translator,
    ?string $id,
    string $label,
    bool $required = TRUE
  ): array {

    $element = [
      '#title' => $label,
      '#type' => 'select',
      '#options' => self::formulaBuildSelectOptions($formula, $translator),
      '#default_value' => $id,
    ];

    if (NULL !== $id
      && !self::idExistsInSelectOptions($id, $element['#options'])
    ) {
      $element['#options'][$id] = t("Unknown id '@id'", ['@id' => $id]);
      $element['#element_validate'][] = static function (array $element, FormStateInterface $form_state) use ($id) {
        if ($id === (string) $element['#value']) {
          $form_state->setError(
            $element,
            t(
              "Unknown id %id. Maybe the id did exist in the past, but it currently does not.",
              ['%id' => $id]
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
   * @param \Donquixote\OCUI\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $translator
   *
   * @return string[][]|string[]
   *   Options to be used in '#options' in a '#type' => 'select' element.
   */
  public static function formulaBuildSelectOptions(
    FormulaBase_AbstractSelectInterface $formula,
    TranslatorInterface $translator
  ): array {
    $options = [];
    // Get the top-level options first.
    foreach ($formula->getOptions(NULL) as $id => $option_label) {
      $options[$id] = $option_label->convert($translator);
    }
    // Build the opt groups.
    foreach ($formula->getOptGroups() as $group_id => $group_label) {
      $group_label_str = $group_label->convert($translator);
      foreach ($formula->getOptions($group_id) as $id => $option_label) {
        $options[$group_label_str][$id] = $option_label->convert($translator);
      }
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
