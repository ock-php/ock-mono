<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Context\CfContext;
use Ock\Ock\Context\CfContextInterface;
use Ock\Ock\Evaluator\Evaluator;
use Ock\Ock\Evaluator\EvaluatorInterface;
use Ock\DID\Exception\EvaluatorException;
use Ock\Ock\Formula\Formula;
use Drupal\Core\Entity\EntityInterface;
use Drupal\ock\UI\Form\Form_GenericRedirectGET;

class EntityDisplay_PreviewForm extends EntityDisplayBase {

  /**
   * @var true[]
   *   Format: $[$urlKey] = TRUE
   */
  private static array $inProgress = [];

  /**
   * @CfrPlugin("previewForm", "Preview form")
   *
   * @return self
   */
  public static function create(
    UniversalAdapterInterface $adapter,
  ): self {
    return new self($adapter, 'entity_display_preview');
  }

  /**
   * @param string $queryKey
   */
  public function __construct(
    private readonly UniversalAdapterInterface $adapter,
    private readonly string $queryKey,
  ) {}

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
      $entityDisplay = Evaluator::fromFormula(
        Formula::iface(EntityDisplayInterface::class),
        $this->adapter,
      )->confGetValue($conf);
      $entityDisplay = $this->adapter->adapt(
        Formula::iface(EntityDisplayInterface::class),
        EvaluatorInterface::class,
      )?->confGetValue($conf);
    }
    catch (AdapterException $e) {
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
      // @todo Report this.
      return $build;
    }

    $build['preview'] = $entityDisplay->buildEntity($entity);

    return $build;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Ock\Ock\Context\CfContextInterface
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
   * @param \Ock\Ock\Context\CfContextInterface $context
   *
   * @return array
   */
  private function buildForm(mixed $conf, CfContextInterface $context): array {

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
