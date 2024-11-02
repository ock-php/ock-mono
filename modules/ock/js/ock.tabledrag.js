(function ($) {
  Drupal.behaviors.ock_orderedIdsTabledrag = {

    attach: function (context) {
      once('ock-tabledrag', '.ock-tabledrag', context)
        .forEach(function (element) {
          var tabledrag = new Drupal.tableDrag(element, {});
          // Suppress the changed warning.
          tabledrag.changed = true;
          // Remove the "Show row weights" control.
          $('> .tabledrag-toggle-weight-wrapper', element.parentNode).remove();
        });
    }
  };
})(jQuery);
