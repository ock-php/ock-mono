<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

abstract class Evaluator_DecoratorBase implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   */
  protected function __construct(EvaluatorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function confGetValue($conf) {
    return $this->decorated->confGetValue($conf);
  }

  /**
   * {@inheritdoc}
   */
  public function confGetPhp($conf): string {
    return $this->decorated->confGetPhp($conf);
  }
}
