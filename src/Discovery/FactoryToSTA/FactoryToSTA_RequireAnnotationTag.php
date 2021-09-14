<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryToSTA;

use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;

class FactoryToSTA_RequireAnnotationTag implements FactoryToSTAInterface {

  /**
   * @var \Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface $decorated
   */
  public function __construct(FactoryToSTAInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?IncarnatorPartialInterface {

    if (!$this->factoryIsSTA($factory)) {
      return NULL;
    }

    return $this->decorated->factoryGetPartial($factory);
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return bool
   */
  private function factoryIsSTA(ReflectionFactoryInterface $factory): bool {

    if (FALSE === $docComment = $factory->getDocComment()) {
      return FALSE;
    }

    return self::docCommentHasArglessAnnotationName($docComment, 'STA');
  }

  /**
   * @param string $docComment
   * @param string $name
   *
   * @return bool
   */
  public static function docCommentHasArglessAnnotationName(string $docComment, string $name): bool {

    if (FALSE === strpos($docComment, '@' . $name)) {
      return FALSE;
    }

    $pattern = ''
      . '~(' . '^/\*\*\h+' . '|' . '\v\h*(\*\h+|)' . ')@'
      . preg_quote($name, '~')
      . '(\(\)|)' . '(\h*\v|\h*\*/$)~';

    if (!preg_match($pattern, $docComment)) {
      return FALSE;
    }

    return TRUE;
  }

}
