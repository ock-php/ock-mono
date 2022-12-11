<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Formula\Select\Grouped\Formula_GroupedSelectInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Donquixote\Ock\Util\ConfUtil;
use Drupal\Component\Render\MarkupInterface;
use Drupal\ock\Formator\Optionable\OptionableFormatorD8Interface;
use Drupal\ock\Formator\Util\D8SelectUtil;

#[Adapter]
class FormatorD8_GroupedSelect implements FormatorD8Interface, OptionableFormatorD8Interface {

  private bool $required = TRUE;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Grouped\Formula_GroupedSelectInterface $formula
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    #[Adaptee]
    private readonly Formula_GroupedSelectInterface $formula,
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {}

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
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return D8SelectUtil::selectElementFromGroupedSelectFormula(
      $this->formula,
      $this->translator,
      ConfUtil::confGetId($conf),
      $label,
      $this->required,
    );
  }
}
