<?php

use Donquixote\ObCK\Text\Text;

return Text::s('ID')
  ->wrapT('@id', 'Unknown id @id')
  ->wrapSprintf('- %s -');
