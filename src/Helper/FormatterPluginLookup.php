<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Drupal\Core\Field\FormatterInterface;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\ock\Attribute\DI\DrupalService;
use Drupal\ock\Attribute\DI\RegisterService;

#[RegisterService(self::SERVICE_ID)]
class FormatterPluginLookup {

  const SERVICE_ID = 'renderkit.formatter_plugin_lookup';

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\Helper\FieldDefinitionLookupInterface $fieldDefinitionLookup
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterPluginManager
   */
  public function __construct(
    #[GetService(FieldDefinitionLookup::SERVICE_ID)]
    private readonly FieldDefinitionLookupInterface $fieldDefinitionLookup,
    #[GetService('plugin.manager.field.formatter')]
    private readonly FormatterPluginManager $formatterPluginManager,
  ) {}

  /**
   * @param string $entityType
   * @param string $fieldName
   * @param string $formatterId
   *
   * @return \Drupal\Core\Field\FormatterInterface|null
   */
  public function getFormatter(string $entityType, string $fieldName, string $formatterId): ?FormatterInterface {
    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $entityType,
      $fieldName,
    );
    if ($fieldDefinition === NULL) {
      return NULL;
    }
    $settings = $this->formatterPluginManager->getDefaultSettings($formatterId);
    $options = [
      'field_definition' => $fieldDefinition,
      'configuration' => [
        'type' => $formatterId,
        'settings' => $settings,
        'label' => '',
        'weight' => 0,
      ],
      'view_mode' => '_custom',
    ];
    return $this->formatterPluginManager->getInstance($options);
  }

}
