<?php
declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\renderkit\Formula\Formula_EntityType_WithFields;
use Drupal\renderkit\Formula\Formula_EtDotX;
use Drupal\renderkit\Formula\Formula_EtDotX_FixedEt;
use Drupal\renderkit\Formula\Formula_FieldName;
use Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEt_FieldName;
use Drupal\renderkit\Formula\Misc\SelectByEt\SelectByEt_FieldName_EntityReference;
use Drupal\Tests\field\Kernel\FieldKernelTestBase;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Ock\Ock\Formula\Select\Formula_SelectInterface;

/**
 * @see \Drupal\KernelTests\Core\Render\Element\RenderElementTypesTest
 */
class FieldNameSelectorsTest extends FieldKernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'system',
    'field',
    'text',
    'entity_test',
    'field_test',
    'renderkit',
    'ock',
    'node',
    'user',
  ];

  /**
   * @throws \Exception
   */
  public function testEnvironment() {

    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $etbi */
    $etbi = $this->container->get('entity_type.bundle.info');

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    # $etm = $this->container->get('entity_type.manager');

    $bundles = $etbi->getAllBundleInfo();

    static::assertArrayHasKey('entity_test', $bundles);

    static::assertSame(
      [
        'entity_test' => ['label' => 'Entity Test Bundle'],
      ],
      $bundles['entity_test']);
  }

  public function testFieldName() {

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'nid' => 'ID',
          'vid' => 'Revision ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Entity reference' => [
          'type' => 'Content type',
          'uid' => 'Authored by',
          'revision_uid' => 'Revision user ID',
        ],
        'Boolean' => [
          'status' => 'Publishing status',
          'promote' => 'Promoted to front page',
          'sticky' => 'Sticky at top of lists',
          'revision_translation_affected' => 'Revision translation affected',
          'default_langcode' => 'Default translation',
        ],
        'Text (plain)' => [
          'title' => 'Title',
        ],
        'Created' => [
          'created' => 'Authored on',
          'revision_timestamp' => 'Revision timestamp',
        ],
        'Last changed' => [
          'changed' => 'Changed',
        ],
        'Text (plain, long)' => [
          'revision_log' => 'Revision log message',
        ],
      ],
      new Formula_FieldName(
        'node',
        'non_existing_bundle'));

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'nid' => 'ID',
          'vid' => 'Revision ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Entity reference' => [
          'type' => 'Content type',
          'uid' => 'Authored by',
          'revision_uid' => 'Revision user ID',
        ],
        'Boolean' => [
          'status' => 'Publishing status',
          'promote' => 'Promoted to front page',
          'sticky' => 'Sticky at top of lists',
          'revision_translation_affected' => 'Revision translation affected',
          'default_langcode' => 'Default translation',
        ],
        'Text (plain)' => [
          'title' => 'Title',
        ],
        'Created' => [
          'created' => 'Authored on',
          'revision_timestamp' => 'Revision timestamp',
        ],
        'Last changed' => [
          'changed' => 'Changed',
        ],
        'Text (plain, long)' => [
          'revision_log' => 'Revision log message',
        ],
      ],
      new Formula_FieldName(
        'node',
        'default'));

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'id' => 'ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Text (plain)' => [
          'type' => '',
          'name' => 'Name',
        ],
        'Created' => [
          'created' => 'Authored on',
        ],
        'Entity reference' => [
          'user_id' => 'User ID',
        ],
        /*
        'Text (formatted)' => [
          'field_test_import_2' => 'Test import field 2 on entity_test bundle',
          'field_test_import' => 'Test import field',
        ],
        */
      ],
      new Formula_FieldName(
        'entity_test',
        'entity_test'));

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'id' => 'ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Text (plain)' => [
          'type' => '',
          'name' => 'Name',
        ],
        'Created' => [
          'created' => 'Authored on',
        ],
        'Entity reference' => [
          'user_id' => 'User ID',
        ],
      ],
      new Formula_FieldName(
        'entity_test',
        'non_existing_bundle'));
  }

  public function testFieldNameAllowedTypes() {

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'nid' => 'ID',
          'vid' => 'Revision ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Entity reference' => [
          'type' => 'Content type',
          'uid' => 'Authored by',
          'revision_uid' => 'Revision user ID',
        ],
        'Boolean' => [
          'status' => 'Publishing status',
          'promote' => 'Promoted to front page',
          'sticky' => 'Sticky at top of lists',
          'revision_translation_affected' => 'Revision translation affected',
          'default_langcode' => 'Default translation',
        ],
        'Text (plain)' => [
          'title' => 'Title',
        ],
        'Created' => [
          'created' => 'Authored on',
          'revision_timestamp' => 'Revision timestamp',
        ],
        'Last changed' => [
          'changed' => 'Changed',
        ],
        'Text (plain, long)' => [
          'revision_log' => 'Revision log message',
        ],
      ],
      new Formula_FieldName_AllowedTypes(
        'node',
        'non_existing_bundle',
        NULL));

    $this->assertFormulaGroupedOptions(
      [
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Boolean' => [
          'status' => 'Publishing status',
          'promote' => 'Promoted to front page',
          'sticky' => 'Sticky at top of lists',
          'revision_translation_affected' => 'Revision translation affected',
          'default_langcode' => 'Default translation',
        ],
      ],
      new Formula_FieldName_AllowedTypes(
        'node',
        // For a non-existing bundle, we only get base fields.
        'non_existing_bundle',
        ['uuid', 'boolean']));

    $this->assertFormulaGroupedOptions(
      [
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Boolean' => [
          'status' => 'Publishing status',
          'promote' => 'Promoted to front page',
          'sticky' => 'Sticky at top of lists',
          'revision_translation_affected' => 'Revision translation affected',
          'default_langcode' => 'Default translation',
        ],
      ],
      new Formula_FieldName_AllowedTypes(
        'node',
        'default',
        ['uuid', 'boolean']));

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'id' => 'ID',
        ],
        'UUID' => [
          'uuid' => 'UUID',
        ],
        'Language' => [
          'langcode' => 'Language',
        ],
        'Text (plain)' => [
          'type' => '',
          'name' => 'Name',
        ],
        'Created' => [
          'created' => 'Authored on',
        ],
        'Entity reference' => [
          'user_id' => 'User ID',
        ],
        /*
        'Text (formatted)' => [
          'field_test_import_2' => 'Test import field 2 on entity_test bundle',
          'field_test_import' => 'Test import field',
        ],
        */
      ],
      new Formula_FieldName_AllowedTypes(
        'entity_test',
        'entity_test',
        null));
  }

  public function testEtDotFieldName() {

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'entity_test.id' => 'ID',
        ],
        'UUID' => [
          'entity_test.uuid' => 'UUID',
        ],
        'Language' => [
          'entity_test.langcode' => 'Language',
        ],
        'Text (plain)' => [
          'entity_test.type' => '',
          'entity_test.name' => 'Name',
        ],
        'Created' => [
          'entity_test.created' => 'Authored on',
        ],
        'Entity reference' => [
          'entity_test.user_id' => 'User ID',
        ],
        /*
        'Text (formatted)' => [
          'entity_test.field_test_import_2' => 'Test import field 2 on entity_test bundle',
          'entity_test.field_test_import' => 'Test import field',
        ],
        */
      ],
      new Formula_EtDotX_FixedEt(
        'entity_test',
        ['entity_test'],
        new SelectByEt_FieldName(),
        'x'));

    $this->assertFormulaGroupedOptions(
      [
        'Number (integer)' => [
          'entity_test.id' => 'ID',
        ],
        'UUID' => [
          'entity_test.uuid' => 'UUID',
        ],
        'Language' => [
          'entity_test.langcode' => 'Language',
        ],
        'Text (plain)' => [
          'entity_test.type' => '',
          'entity_test.name' => 'Name',
        ],
        'Created' => [
          'entity_test.created' => 'Authored on',
        ],
        'Entity reference' => [
          'entity_test.user_id' => 'User ID',
        ],
        /*
        'Text (formatted)' => [
          'entity_test.field_test_import_2' => 'Test import field 2 on test bundle | Test import field 2 on entity_test bundle',
          'entity_test.field_test_import' => 'Test import field',
        ],
        */
      ],
      new Formula_EtDotX_FixedEt(
        'entity_test',
        NULL,
        new SelectByEt_FieldName(),
        'x'));

    $this->assertFormulaGroupedOptions(
      [
        'User' => [
          'user.roles' => 'Roles (Entity reference)',
        ],
        'Test entity with default access' => [
          'entity_test_default_access.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - data table - langcode key' => [
          'entity_test_mul_langcode_key.user_id' => 'User ID (Entity reference)',
        ],
        'Entity Test view builder' => [
          'entity_test_view_builder.user_id' => 'User ID (Entity reference)',
        ],
        'Entity Test with a multivalue base field' => [
          'entity_test_multivalue_basefield.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity constraint violation' => [
          'entity_test_constraint_violation.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity field overrides' => [
          'entity_test_field_override.user_id' => 'User ID (Entity reference)',
        ],
        'Entity Test without id' => [
          'entity_test_no_id.user_id' => 'User ID (Entity reference)',
        ],
        'Entity Test without label' => [
          'entity_test_no_label.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - data table' => [
          'entity_test_mul_default_value.user_id' => 'User ID (Entity reference)',
          'entity_test_field_methods.user_id' => 'User ID (Entity reference)',
          'entity_test_mul.user_id' => 'User ID (Entity reference)',
          'entity_test_mul_changed.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity with bundle' => [
          'entity_test_with_bundle.type' => ' (Entity reference)',
        ],
        'Entity Test label' => [
          'entity_test_label.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity constraints' => [
          'entity_test_constraints.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity' => [
          'entity_test.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - revisions log' => [
          'entity_test_revlog.revision_user' => 'Revision user (Entity reference)',
        ],
        'Entity test label callback' => [
          'entity_test_label_callback.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - revisions log and data table' => [
          'entity_test_mulrev_chnged_revlog.user_id' => 'User ID (Entity reference)',
          'entity_test_mulrev_chnged_revlog.revision_user' => 'Revision user (Entity reference)',
        ],
        'Test entity with string_id' => [
          'entity_test_string_id.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity with field cache' => [
          'entity_test_cache.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - base field display' => [
          'entity_test_base_field_display.user_id' => 'User ID (Entity reference)',
        ],
        'Entity Test without bundle' => [
          'entity_test_no_bundle.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - revisions and data table' => [
          'entity_test_mulrev_changed.user_id' => 'User ID (Entity reference)',
          'entity_test_mulrev.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - revisions, data table, and published interface' => [
          'entity_test_mulrevpub.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity for default values' => [
          'entity_test_default_value.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - admin routes' => [
          'entity_test_admin_routes.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity - revisions' => [
          'entity_test_rev.user_id' => 'User ID (Entity reference)',
        ],
        'Test entity constraints with composite constraint' => [
          'entity_test_composite_constraint.user_id' => 'User ID (Entity reference)',
        ],
        'Content' => [
          'node.type' => 'Content type (Entity reference)',
          'node.uid' => 'Authored by (Entity reference)',
          'node.revision_uid' => 'Revision user ID (Entity reference)',
        ],
      ],
      new Formula_EtDotX(
        Formula_EntityType_WithFields::create(),
        new SelectByEt_FieldName_EntityReference(),
        'x'));
  }

  /**
   * @param string[][] $expected
   *   Format: $[$optgroupLabel][$id] = $optionLabel
   * @param \Ock\Ock\Core\Formula\FormulaInterface $formula
   */
  private function assertFormulaGroupedOptions(array $expected, FormulaInterface $formula): void {

    if ($formula instanceof Formula_Proxy_Cache_SelectBase) {

      $this->assertGroupedOptions(
        $expected,
        $formula->getData());

      return;
    }

    $replacement = $this->formulaReplacer()->formulaGetReplacement($formula);

    if (NULL !== $replacement) {
      if (!$replacement instanceof Formula_SelectInterface) {
        $formulaClass = \get_class($formula);
        $replacementClass = \get_class($replacement);
        static::fail("Unexpected formula replacement $replacementClass for $formulaClass.");
        return;
      }

      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $formula = $replacement;
    }
    elseif (!$formula instanceof Formula_SelectInterface) {
      $formulaClass = \get_class($formula);
      static::fail("Unexpected formula $formulaClass.");
      return;
    }


    $this->assertGroupedOptions(
      $expected,
      $formula->getGroupedOptions());
  }

  /**
   * @param string[][] $expected
   * @param mixed[][] $actualWithMarkup
   */
  private function assertGroupedOptions(array $expected, array $actualWithMarkup): void {

    $actual = [];
    foreach ($actualWithMarkup as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $k => $label) {
        $actual[$groupLabel][$k] = (string)$label;
      }
    }

    static::assertSame(
      $expected,
      $actual);
  }

  /**
   * @return \Ock\Ock\FormulaReplacer\FormulaReplacerInterface
   */
  private function formulaReplacer(): FormulaReplacerInterface {
    return CfrPluginHub::getContainer()->formulaReplacer;
  }

  /** @noinspection PhpUnusedPrivateMethodInspection */
  /**
   * @return \Ock\Ock\Incarnator\IncarnatorInterface
   */
  private function sta(): IncarnatorInterface {
    return CfrPluginHub::getContainer()->incarnator;
  }

}
