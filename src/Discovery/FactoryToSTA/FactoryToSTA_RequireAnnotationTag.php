<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\FactoryToSTA;

use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

class FactoryToSTA_RequireAnnotationTag implements FactoryToSTAInterface {

  /**
   * @var \Donquixote\OCUI\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Discovery\FactoryToSTA\FactoryToSTAInterface $decorated
   */
  public function __construct(FactoryToSTAInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SchemaToAnythingPartialInterface {

    if (!$this->factoryIsSTA($factory)) {
      return null;
    }

    return $this->decorated->factoryGetPartial($factory);
  }

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return bool
   */
  private function factoryIsSTA(ReflectionFactoryInterface $factory): bool {

    if (false === $docComment = $factory->getDocComment()) {
      return false;
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

    if (false === strpos($docComment, '@' . $name)) {
      return false;
    }

    $pattern = ''
      . '~(' . '^/\*\*\h+' . '|' . '\v\h*(\*\h+|)' . ')@'
      . preg_quote($name, '~')
      . '(\(\)|)' . '(\h*\v|\h*\*/$)~';

    if (!preg_match($pattern, $docComment)) {
      return false;
    }

    return true;
  }
}
