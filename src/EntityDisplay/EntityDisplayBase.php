<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Convenience base class for entity display handlers.
 *
 * Allows deriving classes to implement buildOne() instead of buildMultiple(),
 * which is usually easier.
 */
abstract class EntityDisplayBase implements EntityDisplayInterface {

  use EntityDisplayBaseTrait;
}
