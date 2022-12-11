<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Label\Formula_LabelInterface;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @param \Donquixote\Ock\Formula\Label\Formula_LabelInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   *
   * @return self
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(
    Formula_LabelInterface $formula,
    UniversalAdapterInterface $adapter,
    #[GetService] TranslatorInterface $translator,
  ): self {
    return new self(
      FormatorD8::fromFormula(
        $formula->getDecorated(),
        $adapter
      ),
      $formula->getLabel(),
      $translator,
    );
  }

  /**
   * @param \Drupal\ock\Formator\FormatorD8Interface $decorated
   * @param \Donquixote\Ock\Text\TextInterface $label
   * @param \Donquixote\Ock\Translator\TranslatorInterface $translator
   */
  public function __construct(
    private readonly FormatorD8Interface $decorated,
    private readonly TextInterface $label,
    private readonly TranslatorInterface $translator,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    return $this->decorated->confGetD8Form(
      $conf,
      $this->label->convert($this->translator),
    );
  }
}
