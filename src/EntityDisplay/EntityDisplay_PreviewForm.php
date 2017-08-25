<?php

namespace Drupal\renderkit8\EntityDisplay;

use Donquixote\Cf\Context\CfContext;
use Donquixote\Cf\Context\CfContextInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\faktoria\Exception\InvalidConfigurationException;
use Drupal\faktoria\Form\Form_GenericRedirectGET;
use Drupal\faktoria\Hub\CfrPluginHub;

class EntityDisplay_PreviewForm extends EntityDisplayBase {

  /**
   * @var true[]
   *   Format: $[$urlKey] = TRUE
   */
  private static $inProgress = [];

  /**
   * @var string
   */
  private $queryKey;

  /**
   * @CfrPlugin("previewForm", "Preview form")
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplay_PreviewForm
   */
  public static function create() {
    return new self('entity_display_preview');
  }

  /**
   * @param string $queryKey
   */
  public function __construct($queryKey) {
    $this->queryKey = $queryKey;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {

    if (!empty(self::$inProgress[$this->queryKey])) {
      return ['#markup' => t('Recursion detected.')];
    }
    self::$inProgress[$this->queryKey] = TRUE;

    try {
      return $this->doBuildEntity($entity);
    }
    finally {
      unset(self::$inProgress[$this->queryKey]);
    }
  }


  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see drupal_get_form()
   * @see drupal_build_form()
   * @see drupal_prepare_form()
   * @see drupal_process_form()
   * @see form_builder()
   */
  private function doBuildEntity(EntityInterface $entity) {

    $conf = isset($_GET[$this->queryKey])
      ? $_GET[$this->queryKey]
      : NULL;

    $context = $this->entityBuildContext($entity);

    $build = [];
    $build['form'] = $this->buildForm($conf, $context);

    if (empty($conf)) {
      return $build;
    }

    try {

      $sta = CfrPluginHub::getContainer()->schemaToAnything;

      $entityDisplay = EntityDisplay::fromConf($conf, $sta);
    }
    catch (InvalidConfigurationException $e) {
      drupal_set_message(t('Failed to construct the EntityDisplay object from the configuration provided.'));
      return $build;
    }

    $build['preview'] = $entityDisplay->buildEntity($entity);

    return $build;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Donquixote\Cf\Context\CfContextInterface
   */
  private function entityBuildContext(EntityInterface $entity) {

    $entityTypeId = $entity->getEntityTypeId();
    $bundle = $entity->bundle();

    return CfContext::create()
      ->paramNameSetValue('entityType', $entityTypeId)
      ->paramNameSetValue('entity_type', $entityTypeId)
      ->paramNameSetValue('bundle', $bundle)
      ->paramNameSetValue('bundle_name', $bundle)
      ->paramNameSetValue('bundleName', $bundle);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return array
   */
  private function buildForm($conf, CfContextInterface $context) {

    $form = [];
    $form[$this->queryKey] = [
      '#title' => t('Entity display plugin'),
      /* @see cfrplugin_element_info() */
      '#type' => 'faktoria',
      '#faktoria_interface' => EntityDisplayInterface::class,
      '#faktoria_context' => $context,
      '#default_value' => $conf,
      '#required' => TRUE,
    ];

    $form['query_key'] = [
      '#type' => 'value',
      '#value' => $this->queryKey,
    ];

    $form['#query_keys'] = [$this->queryKey];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Show'),
    ];

    // @todo Form submit is not working.

    /** @noinspection PhpMethodParametersCountMismatchInspection */
    return \Drupal::formBuilder()->getForm(
      Form_GenericRedirectGET::class,
      $form);
  }
}
