
(function($) {

  function extractValues(formValuesAll, prefix_search, prefix_replace) {

    var values = {};
    for (var i = 0; i < formValuesAll.length; ++i) {
      var vv = formValuesAll[i];
      if (prefix_search !== vv.name.substr(0, prefix_search.length)) {
        continue;
      }
      var localname = prefix_replace + vv.name.substr(prefix_search.length);
      values[localname] = vv.value;
    }

    return values;
  }

  Drupal.behaviors.ock_preset = {
    attach: function (context, settings) {
      $('.ock_preset-tools', context).each(function() {
        var $tools = $(this);
        console.log(this);
        var $drilldown = $tools.parents('.ock_preset-drilldown').first();
        var $select = $('> * > select.ock_preset-drilldown-select', $drilldown).first();
        var $presetLink = $('a.ock_preset-link', $tools);
        var $form = $select.parents('form').first();
        var selectName = $select.attr('name');
        console.log(selectName, $presetLink[0]);
        if (0
          || 1 !== $drilldown.length
          || 1 !== $select.length
          || 1 !== $presetLink.length
          || 1 !== $form.length
          || '[id]' !== selectName.substr(-4)
        ) {
          return;
        }

        var name = selectName.slice(0, -4);

        var f = function() {
          var formValuesAll = $form.serializeArray();
          var values = extractValues(formValuesAll, name + '[options]', 'conf[options]');
          console.log(formValuesAll, values);
          values['conf[id]'] = $select.val();
          $presetLink[0].search = '?' + $.param(values);
        };

        $presetLink.mousedown(f);
        $presetLink.click(f);
      });
    }
  };
})(jQuery);
