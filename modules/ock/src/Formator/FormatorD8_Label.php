<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Formula\Label\Formula_LabelInterface;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\Translator\TranslatorInterface;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_Label implements FormatorD8Interface {

  /**
   * @param \Ock\Ock\Formula\Label\Formula_LabelInterface $formula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
   *
   * @return self
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
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
   * @param \Ock\Ock\Text\TextInterface $label
   * @param \Ock\Ock\Translator\TranslatorInterface $translator
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
