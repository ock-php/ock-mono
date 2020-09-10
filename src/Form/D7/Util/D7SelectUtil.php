<?php
declare(strict_types=1);

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface;
use Donquixote\Cf\Util\UtilBase;

final class D7SelectUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface $schema
   * @param string|int $id
   * @param string $label
   * @param bool $required
   *
   * @return array
   */
  public static function optionsSchemaBuildSelectElement(
    CfSchemaBase_AbstractSelectInterface $schema,
    $id,
    $label,
    $required = TRUE
  ): array {
    return self::groupedOptionsBuildSelectElement(
      $schema->getGroupedOptions(),
      $id,
      $label,
      $required
    );
  }

  /**
   * @param string[][] $groupedOptions
   * @param $id
   * @param $label
   * @param bool $required
   *
   * @return array
   */
  public static function groupedOptionsBuildSelectElement(
    array $groupedOptions,
    $id,
    $label,
    $required = TRUE
  ): array {
    $element = [
      '#title' => $label,
      '#type' => 'select',
      '#options' => self::groupedOptionsGetSelectOptions($groupedOptions),
      '#default_value' => $id,
    ];

    if (NULL !== $id
      && !self::idExistsInSelectOptions($id, $element['#options'])) {
      $element['#options'][$id] = t("Unknown id '@id'", ['@id' => $id]);
      $element['#element_validate'][] = function (array $element) use ($id) {
        if ((string) $id === (string) $element['#value']) {
          form_error($element, t("Unknown id %id. Maybe the id did exist in the past, but it currently does not.", ['%id' => $id]));
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
   * @param \Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractSelectInterface $schema
   *
   * @return string[]|string[][]
   */
  public static function optionsSchemaGetSelectOptions(CfSchemaBase_AbstractSelectInterface $schema): array {

    return self::groupedOptionsGetSelectOptions($schema->getGroupedOptions());
  }

  /**
   * @param string[][] $groupedOptions
   *
   * @return string[]|string[][]
   */
  public static function groupedOptionsGetSelectOptions(array $groupedOptions): array {

    $options = $groupedOptions;

    if (isset($groupedOptions[''])) {
      $options = $groupedOptions[''] + $options;
    }

    unset($options['']);

    return $options;
  }

  /**
   * @param string $id
   * @param array $options
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
