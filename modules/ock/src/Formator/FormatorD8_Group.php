<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\Group\GroupHelper;
use Donquixote\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;

#[Adapter]
class FormatorD8_Group implements FormatorD8Interface {

  /**
   * @var \Donquixote\Ock\Formula\Group\GroupHelper
   *
   * @todo Make this a service?
   */
  private GroupHelper $groupHelper;

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    #[Adaptee]
    private readonly Formula_GroupInterface $groupFormula,
    #[UniversalAdapter]
    UniversalAdapterInterface $adapter,
    #[GetService]
    private readonly TranslatorInterface $translator,
  ) {
    $this->groupHelper = new GroupHelper($adapter);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {

    if (!\is_array($conf)) {
      $conf = [];
    }

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    try {
      $resolvedGroup = $this->groupHelper
        ->withOriginalItems($this->groupFormula->getItems())
        ->withConf($conf);
    }
    catch (FormulaException $e) {
      // Misbehaving formula.
      // @todo Create failing element.
      return [];
    }

    foreach ($resolvedGroup->getKeys() as $itemKey) {
      try {
        $itemLabel = $resolvedGroup->keyGetLabel($itemKey)->convert($this->translator);
        $itemFormator = $resolvedGroup->keyGetObject($itemKey, FormatorD8Interface::class);
      }
      catch (FormulaException|AdapterException $e) {
        // @todo Insert failing element.
        continue;
      }
      // @todo Ajaxify!
      // @todo Reset item configuration, if dependee config has changed.
      $itemConf = $conf[$itemKey] ?? null;
      $form[$itemKey] = $itemFormator->confGetD8Form($itemConf, $itemLabel);
    }

    $form['#attached']['library'][] = 'ock/form';

    return $form;
  }
}
