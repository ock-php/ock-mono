<?php

use Donquixote\Ock\Text\Text;

return Text::s('ID')
  ->wrapT('@id', 'Unknown id @id')
  ->wrapSprintf('- %s -');
