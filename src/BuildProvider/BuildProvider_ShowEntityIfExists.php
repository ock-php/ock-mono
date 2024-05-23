<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Donquixote\DID\Attribute\Parameter\GetCallableService;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginFormula;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\DID\Exception\EvaluatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;
use Donquixote\DID\Util\PhpUtil;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ock\Util\DrupalPhpUtil;
use Drupal\renderkit\EntityDisplay\EntityDisplay;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\Formula\Formula_EntityIdAutocomplete;
use Drupal\renderkit\Formula\Formula_EntityType_WithGroupLabels;

/**
 * @todo Use an EntityProviderInterface object, instead of choosing type + id.
 */
class BuildProvider_ShowEntityIfExists implements BuildProviderInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param string $entityTypeId
   * @param string|int $entityId
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly string $entityTypeId,
    private readonly string|int $entityId,
    private readonly EntityDisplayInterface $entityDisplay,
  ) {}

  #[OckPluginInstance('entityDisplayNode1', 'Show node 1')]
  public static function createNode1(
    #[GetService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
    #[OckOption('entity_display', 'Display')]
    EntityDisplayInterface $entityDisplay,
  ): self {
    return new self($entityTypeManager, 'node', 1, $entityDisplay);
  }

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param callable(string): \Drupal\renderkit\Formula\Formula_EntityIdAutocomplete $entityIdFormulaMap
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[OckPluginFormula(self::class, 'entityDisplay', 'Show an entity')]
  public static function formula(
    #[GetService('entity_type.manager')]
    EntityTypeManagerInterface $entityTypeManager,
    #[GetCallableService(Formula_EntityIdAutocomplete::class)]
    callable $entityIdFormulaMap,
  ): FormulaInterface {
    return Formula::group()
      ->add(
        'entity_type',
        Text::t('Entity type'),
        Formula_EntityType_WithGroupLabels::create(),
      )
      ->addDynamicFormula(
        'entity_id',
        Text::t('Entity ID'),
        ['entity_type'],
        $entityIdFormulaMap,
      )
      ->addDynamicFormula(
        'entity_display',
        Text::t('Entity display'),
        ['entity_type', 'entity_id'],
        function (
          string $entityType,
          string|int $entity_id,
        ) use ($entityTypeManager): FormulaInterface {
          // @todo Handle failures.
          $storage = $entityTypeManager->getStorage($entityType);
          $entity = $storage->load($entity_id);
          $bundle = $entity->bundle();
          return EntityDisplay::formula($entityType, $bundle);
        },
      )
      ->buildPhp(PhpUtil::phpConstruct(self::class, [
        DrupalPhpUtil::service('entity_type.manager'),
        PhpUtil::phpPlaceholder('entity_type'),
        PhpUtil::phpPlaceholder('entity_id'),
        PhpUtil::phpPlaceholder('entity_display'),
      ]));
  }

  /**
   * @return array
   *   A render array.
   *
   * @throws \Exception
   */
  public function build(): array {
    try {
      $storage = $this->entityTypeManager->getStorage($this->entityTypeId);
    }
    catch (InvalidPluginDefinitionException $e) {
      throw new \Exception("No entity type storage found for '$this->entityTypeId'.", 0, $e);
    }

    if (NULL === $entity = $storage->load($this->entityId)) {
      throw new EvaluatorException("Entity $this->entityTypeId:$this->entityId does not exist.");
    }

    return $this->entityDisplay->buildEntity($entity);
  }
}
