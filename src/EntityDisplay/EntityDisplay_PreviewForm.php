<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Donquixote\Ock\Context\CfContext;
use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Exception\EvaluatorException;
use Drupal\Core\Entity\EntityInterface;
use Drupal\ock\Form\Form_GenericRedirectGET;

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
   * @return self
   */
  public static function create(): self {
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
  public function buildEntity(EntityInterface $entity): array {

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
  private function doBuildEntity(EntityInterface $entity): array {

    $conf = $_GET[$this->queryKey] ?? null;

    $context = $this->entityBuildContext($entity);

    $build = [];
    $build['form'] = $this->buildForm($conf, $context);

    if (empty($conf)) {
      return $build;
    }

    try {

      $sta = CfrPluginHub::getContainer()->formulaToAnything;

      $entityDisplay = EntityDisplay::fromConf($conf, $sta);
    }
    catch (UnsupportedFormulaException $e) {
      // @todo Log this.
      unset($e);
      \Drupal::messenger()->addMessage(t('Unsupported formula.'));
      return $build;
    }
    catch (EvaluatorException $e) {
      // @todo Log this.
      unset($e);
      \Drupal::messenger()->addMessage(t('Failed to construct the EntityDisplay object from the configuration provided.'));
      return $build;
    }

    if (null === $entityDisplay) {
      // @ŧodo
      return $build;
    }

    $build['preview'] = $entityDisplay->buildEntity($entity);

    return $build;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Donquixote\Ock\Context\CfContextInterface
   */
  private function entityBuildContext(EntityInterface $entity): CfContextInterface {

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
   * @param \Donquixote\Ock\Context\CfContextInterface $context
   *
   * @return array
   */
  private function buildForm($conf, CfContextInterface $context): array {

    $form = [];
    $form[$this->queryKey] = [
      '#title' => t('Entity display plugin'),
      /* @see \Drupal\ock\Element\FormElement_CuPlugin() */
      '#type' => 'cu',
      '#cu_interface' => EntityDisplayInterface::class,
      '#cu_context' => $context,
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

    return \Drupal::formBuilder()->getForm(
      Form_GenericRedirectGET::class,
      $form);
  }
}
