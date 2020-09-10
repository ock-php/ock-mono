<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\DocToAnnotations;

use Donquixote\Annotation\Resolver\AnnotationResolver;
use Donquixote\Annotation\Resolver\AnnotationResolverInterface;
use Donquixote\Annotation\Util\DocCommentUtil;
use Donquixote\Annotation\Value\GenericAnnotation\GenericAnnotationInterface;
use Drupal\Component\Render\MarkupInterface;

class DocToAnnotations implements DocToAnnotationsInterface {

  /**
   * @var string
   */
  private $tagName;

  /**
   * @var \Donquixote\Annotation\Resolver\AnnotationResolverInterface
   */
  private $annotationResolver;

  /**
   * @param string $tagName;
   *
   * @return self
   */
  public static function create($tagName): DocToAnnotations {
    return new self($tagName, AnnotationResolver::createGeneric());
  }

  /**
   * @param string $tagName
   * @param \Donquixote\Annotation\Resolver\AnnotationResolverInterface $annotationResolver
   */
  public function __construct($tagName, AnnotationResolverInterface $annotationResolver) {
    $this->tagName = $tagName;
    $this->annotationResolver = $annotationResolver;
  }

  /**
   * {@inheritdoc}
   */
  public function docGetAnnotations(string $docComment): array {

    if (false === strpos($docComment, '@' . $this->tagName)) {
      return [];
    }

    $annotations = [];
    foreach (DocCommentUtil::docGetGenericAnnotationObjects(
      $docComment,
      $this->tagName,
      $this->annotationResolver
    ) as $object) {

      $annotation = [];
      foreach ($object->getArguments() as $k => $v) {
        if ($v instanceof GenericAnnotationInterface) {
          if (null === $v = $this->resolveAnnotation($v->getName(), $v->getArguments())) {
            continue;
          }
        }
        elseif (\is_object($v)) {
          continue;
        }
        $annotation[$k] = $v;
      }

      $annotations[] = $annotation;
    }

    return $annotations;
  }

  /**
   * @param string $name
   * @param array $args
   *
   * @return mixed|null
   */
  private function resolveAnnotation($name, array $args) {

    if ($name === \t::class || $name === \Translate::class) {
      if (isset($args[0]) && \is_string($args[0])) {
        $translated = t($args[0]);
        if ($translated instanceof MarkupInterface) {
          return $translated->render();
        }
        if (\is_string($translated)) {
          return $translated;
        }
        return $args[0];
      }
    }

    return NULL;

  }
}
