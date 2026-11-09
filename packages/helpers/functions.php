<?php

foreach (scandir(__DIR__ . '/functions') as $candidate) {
  if (!preg_match('#^functions\.\w+\.php$#', $candidate)) {
    continue;
  }
  require_once __DIR__ . '/functions/' . $candidate;
}
