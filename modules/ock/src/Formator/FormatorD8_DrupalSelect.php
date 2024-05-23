<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Translator\TranslatorInterface;
use Ock\Ock\Util\ConfUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\Controlling\ControllingFormatorInterface;
use Drupal\ock\Formator\Util\D8SelectUtil;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelect_FromCommonSelect;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelect_FromFlatSelect;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

class FormatorD8_DrupalSelect implements FormatorD8Interface, ControllingFormatorInterface {

  /**
   * @var bool
   */
  private bool $required = TRUE;

  /**
   * @param \Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   *
   * @return self
   */
  #[Adapter]
  public static function createFlat(
    Formula_FlatSelectInterface $formula,
    #[GetService] TranslatorInterface $translator,
  ): self {
    return new self(
      new Formula_DrupalSelect_FromFlatSelect($formula, $translator),
    );
  }

  /**
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $formula
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   *
   * @return self
   */
  #[Adapter]
  public static function fromCommonSelect(
    Formula_SelectInterface $formula,
    #[GetService] TranslatorInterface $translator,
  ): self {
    return new self(
      new Formula_DrupalSelect_FromCommonSelect($formula, $translator),
    );
  }

  /**
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   *
   * @return self
   */
  #[Adapter]
  public static function create(Formula_DrupalSelectInterface $formula): self {
    return new self($formula);
  }

  /**
   * Constructor.
   *
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   */
  public function __construct(
    private readonly Formula_DrupalSelectInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildControllingSubform(mixed $conf, MarkupInterface|string|null $label, array $ajax): array {
    $subform = $this->confGetD8Form($conf, $label);
    $subform['#ajax'] = $ajax;
    return $subform;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return D8SelectUtil::selectElementFromDrupalSelectFormula(
      $this->formula,
      ConfUtil::confGetId($conf),
      $label,
      $this->required,
    );
  }

}
