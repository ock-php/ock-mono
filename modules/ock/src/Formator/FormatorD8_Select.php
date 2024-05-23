<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Formula\Select\Formula_Select_FromFlatSelect;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\Translator\Translator;
use Ock\Ock\Util\ConfUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Drupal\ock\Formator\Util\D8SelectUtil;

class FormatorD8_Select implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var bool
   */
  private bool $required = TRUE;

  /**
   * @param \Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   *
   * @return self
   */
  #[Adapter]
  public static function createFlat(Formula_FlatSelectInterface $formula): FormatorD8_Select {
    return new self(
      new Formula_Select_FromFlatSelect($formula));
  }

  /**
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $formula
   *
   * @return self
   */
  #[Adapter]
  public static function create(Formula_SelectInterface $formula): self {
    return new self($formula);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptionalFormator(): ?FormatorD8Interface {
    if (!$this->required) {
      return NULL;
    }
    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $formula
   */
  public function __construct(
    private Formula_SelectInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    $translator = Translator::passthru();
    return D8SelectUtil::selectElementFromCommonSelectFormula(
      $this->formula,
      $translator,
      ConfUtil::confGetId($conf),
      $label,
      $this->required
    );
  }
}
