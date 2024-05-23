<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\DefaultConf\Formula_DefaultConf;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelect_Fixed;
use Donquixote\Ock\Text\Text;
use Donquixote\DID\Util\PhpUtil;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\ock\Util\DrupalPhpUtil;
use Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Formula\Formula_FieldFormatterId;
use Drupal\renderkit\Formula\Formula_FieldFormatterSettings;
use Drupal\renderkit\Helper\FieldDefinitionLookupInterface;
use Drupal\renderkit\Helper\FormatterPluginLookup;

/**
 * Entity display handler to view a specific field on all the entities.
 */
class EntityDisplay_FieldWithFormatter extends EntityDisplayBase {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityType
   * @param string $fieldName
   * @param array $display
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface|null $fieldDisplayProcessor
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly string $entityType,
    private readonly string $fieldName,
    private readonly array $display,
    private readonly ?FieldDisplayProcessorInterface $fieldDisplayProcessor = NULL,
  ) {}

  /**
   * @param \Drupal\renderkit\Formula\Formula_EtDotFieldName $fieldNameFormula
   * @param \Drupal\renderkit\Helper\FieldDefinitionLookupInterface $fieldDefinitionLookup
   * @param \Drupal\renderkit\Formula\Formula_FieldFormatterId $formatterIdFormula
   * @param \Drupal\renderkit\Helper\FormatterPluginLookup $formatterPluginLookup
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(self::class, 'fieldWithFormatter', 'Field with formatter')]
  public static function formula(
    #[GetService]
    Formula_EtDotFieldName $fieldNameFormula,
    #[GetService]
    FieldDefinitionLookupInterface $fieldDefinitionLookup,
    #[GetService]
    Formula_FieldFormatterId $formatterIdFormula,
    #[GetService]
    FormatterPluginLookup $formatterPluginLookup,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'field',
        Text::t('Field'),
        $fieldNameFormula,
      )
      ->addStringParts(['entity_type', 'field_name'], '.', 'field')
      ->add(
        'label',
        Text::t('Label display'),
        new Formula_DefaultConf(
          new Formula_FlatSelect_Fixed([
            'above' => Text::t('Above'),
            'inline' => Text::t('Inline'),
            'hidden' => Text::t('Hidden')->wrapSprintf('<%s>'),
          ]),
          'hidden',
        ),
      )
      ->addDynamicFormula(
        'formatter',
        Text::t('Formatter'),
        ['entity_type', 'field_name'],
        function (
          string $entityType,
          string $fieldName,
        ) use ($fieldDefinitionLookup, $formatterIdFormula): ?FormulaInterface {
          $fieldDefinition = $fieldDefinitionLookup->etAndFieldNameGetDefinition($entityType, $fieldName);
          if ($fieldDefinition === null) {
            return NULL;
          }
          return $formatterIdFormula->withFieldType($fieldDefinition->getType());
        },
      )
      ->addDynamicFormula(
        'settings',
        Text::t('Formatter settings'),
        ['entity_type', 'field_name', 'formatter'],
        function (
          string $entityType,
          string $fieldName,
          string $formatterId,
        ) use (
          $fieldDefinitionLookup,
          $formatterPluginLookup,
          $formatterIdFormula,
        ): ?FormulaInterface {
          $formatter = $formatterPluginLookup->getFormatter(
            $entityType,
            $fieldName,
            $formatterId,
          );
          if ($formatter === null) {
            return null;
          }
          return new Formula_FieldFormatterSettings($formatter);
        },
      )
      ->add(
        'processor',
        Text::t('Field display processor'),
        Formula::ifaceOrNull(FieldDisplayProcessorInterface::class),
      )
      ->constructPhp(self::class, [
        DrupalPhpUtil::service('entity_type.manager'),
        PhpUtil::phpPlaceholder('entity_type'),
        PhpUtil::phpPlaceholder('field_name'),
        PhpUtil::phpArray([
          'label' => PhpUtil::phpPlaceholder('label'),
          'type' => PhpUtil::phpPlaceholder('formatter'),
          'settings' => PhpUtil::phpPlaceholder('settings'),
          'weight' => '0',
        ]),
        PhpUtil::phpPlaceholder('processor'),
      ]);
  }


  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  final public function buildEntity(EntityInterface $entity): array {

    if (!$entity instanceof FieldableEntityInterface) {
      return [];
    }

    if ($this->entityType !== $entity->getEntityTypeId()) {
      return [];
    }

    $fieldItemList = $entity->get($this->fieldName);

    if ($fieldItemList->isEmpty()) {
      return [];
    }

    $build = $this->entityTypeManager
      ->getViewBuilder($this->entityType)
      ->viewField($fieldItemList, $this->display);

    if (NULL !== $this->fieldDisplayProcessor) {
      $build = $this->fieldDisplayProcessor->process($build);
    }

    return $build;
  }

}
