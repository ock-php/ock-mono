(function ($) {
  Drupal.behaviors.ock_orderedIdsTabledrag = {

    attach: function (context) {
      $('.ock-tabledrag', context)
        .once('ock-tabledrag')
        .each(function () {
          console.log(this);
          var tabledrag = new Drupal.tableDrag(this, {});
          // Suppress the changed warning.
          tabledrag.changed = true;
          // Remove the "Show row weights" control.
          $('> .tabledrag-toggle-weight-wrapper', this.parentNode).remove();
        });
    }
  };
})(jQuery);
