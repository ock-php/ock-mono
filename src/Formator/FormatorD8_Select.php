<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Drupal\cu\Formator\Optionable\OptionableFormatorD8Interface;
use Drupal\cu\Formator\Util\D8SelectUtil;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromFlatSelect;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface;
use Donquixote\ObCK\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\ObCK\Translator\Translator;
use Donquixote\ObCK\Util\ConfUtil;

class FormatorD8_Select implements FormatorD8Interface, OptionableFormatorD8Interface {

  /**
   * @var \Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface
   */
  private $formula;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface $formula
   *
   * @return self
   */
  public static function createFlat(Formula_FlatSelectInterface $formula): FormatorD8_Select {
    return new self(
      new Formula_Select_FromFlatSelect($formula));
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $formula
   *
   * @return self
   */
  public static function create(Formula_SelectInterface $formula): FormatorD8_Select {
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
   * @param \Donquixote\ObCK\FormulaBase\FormulaBase_AbstractSelectInterface $formula
   */
  public function __construct(FormulaBase_AbstractSelectInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form($conf, $label): array {
    $translator = new Translator(new TranslatorLookup_Passthru());
    return D8SelectUtil::optionsFormulaBuildSelectElement(
      $this->formula,
      $translator,
      ConfUtil::confGetId($conf),
      $label,
      $this->required
    );
  }
}
