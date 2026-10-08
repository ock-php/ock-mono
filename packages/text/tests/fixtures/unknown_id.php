<?php

declare(strict_types=1);

use Ock\Text\Text;

return Text::s('ID')
  ->wrapT('@id', 'Unknown id @id')
  ->wrapSprintf('- %s -');
