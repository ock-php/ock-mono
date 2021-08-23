(function ($) {
  Drupal.behaviors.cu_orderedIdsTabledrag = {

    attach: function (context) {
      $('.cu-tabledrag', context)
        .once('cu-tabledrag')
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
