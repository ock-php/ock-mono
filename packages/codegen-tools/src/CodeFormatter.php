<?php

declare(strict_types = 1);

namespace Ock\CodegenTools;

class CodeFormatter {

  public function __construct(
    private LineBreaker $lineBreaker,
    private readonly Aliasifier $aliasifier,
  ) {}

  public static function create(): static {
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static(new LineBreaker(), new Aliasifier());
  }

  public function withMaxLineLength(int $limit): static {
    $clone = clone $this;
    $clone->lineBreaker = $this->lineBreaker->withMaxLineLength($limit);
    return $clone;
  }

  /**
   * @param string $level
   *
   * @return static
   */
  public function withIndentLevel(string $level): static {
    $clone = clone $this;
    $clone->lineBreaker = $this->lineBreaker->withIndentLevel($level);
    return $clone;
  }

  /**
   * Formats the code as a php file with open tag and strict types declaration.
   *
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  public function formatAsFile(string $php, string $namespace = NULL): string {
    $filePhp = "<?php\n\ndeclare(strict_types=1);\n\n"
      . self::formatAsSnippet($php, $namespace);
    if (!str_ends_with($filePhp, "\n")) {
      // Files should always end with a line break.
      $filePhp .= "\n";
    }
    return $filePhp;
  }

  /**
   * Formats the code as a snippet without php open tag.
   *
   * @param string $phpExpression
   *
   * @return string
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  public function formatExpressionAsSnippet(string $phpExpression): string {
    $statement = 'return ' . $phpExpression . ';';
    return self::formatAsSnippet($statement);
  }

  /**
   * Formats the code as a snippet without php open tag.
   *
   * This can be used e.g. to embed in a yml file.
   *
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   *
   * @throws \Ock\CodegenTools\Exception\CodegenException
   */
  public function formatAsSnippet(string $php, string $namespace = NULL): string {
    $php = $this->aliasifier->aliasify($php)->getImportsPhp() . $php;

    if (NULL !== $namespace) {
      $php = 'namespace ' . $namespace . ";\n\n" . $php;
    }

    $php = $this->lineBreaker->breakLongLines($php);

    return $php;
  }

}
