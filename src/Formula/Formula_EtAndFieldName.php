<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;
use Donquixote\ObCK\Text\Text;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreInterface;
use Drupal\renderkit\IdToFormula\IdToFormula_Et_FieldName;
use Drupal\renderkit\Util\UtilBase;
use Psr\Container\ContainerInterface;

/**
 * Formula where the value is like ['entity_type' => 'node', 'field_name' => 'body'].
 */
final class Formula_EtAndFieldName extends UtilBase {

  /**
   * @param \Psr\Container\ContainerInterface $container
   * @param array|null $allowed_types
   * @param string|null $entity_type_id
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function fromContainer(
    ContainerInterface $container,
    array $allowed_types = NULL,
    string $entity_type_id = NULL,
    string $bundle = NULL
  ): FormulaInterface {
    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $key_value_factory = $container->get('keyvalue');
    return self::create(
      $container->get('entity_field.manager'),
      $container->get('plugin.manager.field.field_type'),
      $key_value_factory->get('entity.definitions.bundle_field_map'),
      $allowed_types,
      $entity_type_id,
      $bundle);
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_manager
   * @param \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bundle_field_map
   * @param array|null $allowed_types
   * @param string|null $entity_type_id
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create(
    EntityFieldManagerInterface $entity_field_manager,
    FieldTypePluginManagerInterface $field_type_manager,
    KeyValueStoreInterface $bundle_field_map,
    array $allowed_types = NULL,
    string $entity_type_id = NULL,
    string $bundle = NULL
  ): FormulaInterface {

    if ($entity_type_id !== NULL) {
      // Build a formula for a fixed entity type.
      return Formula::group()
        // Use an optionless formula for the entity type.
        ->add(
          'entity_type',
          new Formula_ValueProvider_FixedValue($entity_type_id),
          Text::t('Entity type'))
        ->add(
          'field_name',
          Formula_FieldName::create(
            $entity_field_manager,
            $field_type_manager,
            $bundle_field_map,
            $entity_type_id,
            $bundle,
            $allowed_types),
          Text::t('Field name'))
        ->build();
    }

    // Build a drilldown formula where the entity type can be selected.
    return Formula_DrilldownVal::createArrify(
      Formula_Drilldown::create(
        Formula_EntityType::create(),
        new IdToFormula_Et_FieldName($allowed_types))
        ->withKeys('entity_type', 'field_name'));

  }
}
