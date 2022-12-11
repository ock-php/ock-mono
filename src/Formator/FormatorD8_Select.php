<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Translator\Translator;
use Donquixote\Ock\Util\ConfUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Drupal\ock\Formator\Util\D8SelectUtil;

class FormatorD8_Select implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Donquixote\Ock\FormulaBase\FormulaBase_AbstractSelectInterface
   */
  private $formula;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   *
   * @return self
   */
  #[Adapter]
  public static function createFlat(Formula_FlatSelectInterface $formula): FormatorD8_Select {
    return new self(
      new Formula_Select_FromFlatSelect($formula));
  }

  /**
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $formula
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
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $formula
   */
  public function __construct(Formula_SelectInterface $formula) {
    $this->formula = $formula;
  }

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
