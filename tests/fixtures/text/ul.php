<?php

use Donquixote\ObCK\Text\Text;

return Text::ul()
  ->add(Text::s('First item'))
  ->add(Text::t('Second item'))
  ->addS('Third item')
  ->addT('Fourth item');
