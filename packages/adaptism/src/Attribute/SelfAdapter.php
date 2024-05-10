<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Attribute;

/**
 * Marks a method as a self-adapter.
 *
 * This means that the `$this` object will be treated as the adaptee.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class SelfAdapter extends AdapterAttributeBase {

}
