<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests;

use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\Generator\Generator;
use Donquixote\OCUI\Generator\Generator_Neutral;
use Donquixote\OCUI\Plugin\Plugin;
use Donquixote\OCUI\Tests\Fixture\IntCondition\IntCondition_GreaterThan;
use Donquixote\OCUI\Tests\Fixture\IntCondition\IntConditionInterface;
use Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Util\LocalPackageUtil;

class DiscoveryTest extends FormulaTestBase {

  /**
   * Tests that class files in the package are discovered.
   */
  public function testClassFilesIA() {
    $root = dirname(__DIR__, 2);

    $class_files = iterator_to_array(
      LocalPackageUtil::getClassFilesIA()->withRealpathRoot(),
      TRUE);

    self::assertSame($class_files[
      $root . '/src/Generator/Generator_Neutral.php'] ?? NULL,
      Generator_Neutral::class);

    self::assertSame($class_files[
      $root . '/src/Formula/Sequence/Formula_SequenceInterface.php'] ?? NULL,
      Formula_SequenceInterface::class);
  }

  /**
   * Tests plugin discovery.
   */
  public function testPluginDiscovery() {
    $registry = $this->getPluginRegistry();
    $pluginss = $registry->getPluginss();

    $formula_to_anything = $this->getFormulaToAnything();

    foreach ($pluginss as $type => $plugins) {
      foreach ($plugins as $id => $plugin) {
        static::assertInstanceOf(
          Plugin::class,
          $plugin,
          "\$pluginss['$type']['$id'] instanceof Plugin.");
        $formula = $plugin->getFormula();
        $generator = Generator::fromFormula($formula, $formula_to_anything);
        static::assertNotNull(
          $generator,
          "Generator created for \$pluginss['$type']['$id'].");
      }
    }

    $plugin = $pluginss[IntConditionInterface::class]['positive'];

    self::assertEquals(
      Text::t('Number is positive'),
      $plugin->getLabel());

    $formula = $plugin->getFormula();
    $generator = Generator::fromFormula(
      $formula,
      $formula_to_anything);

    self::assertNotNull($generator, 'Generator not NULL.');

    self::assertSame(
      '\\' . IntCondition_GreaterThan::class . '::positive()',
      $generator->confGetPhp(NULL));

    $formula = Formula::iface(IntOpInterface::class);

    $generator = Generator::fromFormula($formula, $formula_to_anything);

    self::assertNotNull($generator, 'Generator not NULL.');
  }

}
