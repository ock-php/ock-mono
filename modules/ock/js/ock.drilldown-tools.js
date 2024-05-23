
(function($) {

  function StoragePerInterface() {

    $(window).bind('storage', function(jqEvent){
      var event = jqEvent.originalEvent;
      if ('ock.' !== event.key.substr(0, 10)) {
        return;
      }
      var iface = event.key.substr(10);
      var values = JSON.parse(jqEvent.newValue);
      update(iface, values);
    });

    function update(iface, values) {
      if (!onChangeCallbacks[iface]) {
        return;
      }
      for (var i = 0; i < onChangeCallbacks[iface].length; ++i) {
        onChangeCallbacks[iface][i](values);
      }
    }

    this.set = function(iface, values) {
      localStorage.setItem('ock.' + iface, JSON.stringify(values));
      update(iface, values);
    };

    this.get = function(iface) {
      return JSON.parse(localStorage.getItem('ock.' + iface));
    };

    var onChangeCallbacks = {};

    this.onChange = function(iface, callback) {
      if (!onChangeCallbacks[iface]) {
        onChangeCallbacks[iface] = [];
      }
      onChangeCallbacks[iface].push(callback);
    };
  }

  var storagePerInterface = new StoragePerInterface();

  /**
   * Finds values in an object where the key starts with a given prefix, and
   * returns an object with these values, with keys where the prefix is
   * replaced.
   *
   * @param {[]} formValuesAll
   * @param {string} prefix_search
   * @param {string} prefix_replace
   * @returns {{}}
   */
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

  /**
   * Finds values in an object where the key starts with a given prefix, and
   * returns an object with these values, with keys where the prefix is
   * replaced.
   *
   * @param {{}} values_old
   * @param {string} prefix_search
   * @param {string} prefix_replace
   * @returns {{}}
   */
  function reprefix(values_old, prefix_search, prefix_replace) {

    var values_new = {};
    for (var k_old in values_old) {
      if (!values_old.hasOwnProperty(k_old)) {
        continue;
      }
      if (prefix_search !== k_old.substr(0, prefix_search.length)) {
        continue;
      }
      var k_new = prefix_replace + k_old.substr(prefix_search.length);
      values_new[k_new] = values_old[k_old];
    }

    return values_new;
  }

  /**
   * @param {string} k
   * @param {string} v
   *
   * @returns {$}
   */
  function createHiddenInput(k, v) {
    var $input = $('<input type="hidden">');
    $input.attr('name', k);
    $input.attr('value', v);
    return $input;
  }

  /**
   * @param {$} $element
   * @param {{}} values
   */
  function insertHiddenInputs($element, values) {
    for (var k in values) {
      createHiddenInput(k, values[k]).appendTo($element);
    }
  }

  Drupal.behaviors.ockDrilldownTools = {
    attach: function (context, settings) {

      $('select.ock-drilldown-select', context).each(function() {
        var $select = $(this);
        var $selectWrapper = $select.parent();
        var $drilldown = $selectWrapper.parent();
        var iface = $drilldown.attr('data:ock_interface');

        var $tools = $('> .ock-tools', $drilldown);
        if (0 === $tools.length) {
          return;
        }

        var selectName = $select.attr('name');
        if ('[id]' !== selectName.substr(-4)) {
          return;
        }
        var name = selectName.slice(0, -4);

        var $form = $($select.parents('form')[0]);

        $tools.appendTo($selectWrapper).show();

        $('.ock-demo', $tools).mousedown(function(event){
          var formValuesAll = $form.serializeArray();
          var values = extractValues(formValuesAll, name + '[options]', 'plugin[options]');
          values['plugin[id]'] = $select.val();
          this.search = '?' + $.param(values);
        });

        $('.ock-inspect', $tools).each(function(event){
          var link = this;
          var $link = $(link);
          var href = link.href;
          var linkText = link.innerHTML;

          function up() {
            var id = $select.val();
            var optionText = $select.find(':selected').text();
            if (!id) {
              $link.hide();
            }
            else {
              link.href = href + '/plugin/' + id;
              link.innerHTML = linkText.replace('@name', optionText);
              $link.show();
            }
          }

          up();
          $select.change(up);
        });

        $('.ock-copy', $tools).click(function(event){
          // event.preventDefault();
          var formValuesAll = $form.serializeArray();
          var values = extractValues(formValuesAll, name + '[options]', 'options');
          values['id'] = $select.val();
          storagePerInterface.set(iface, values);
          return false;
        });

        $('.ock-paste', $tools).each(function() {

          var $paste = $(this);

          $paste.click(function(){
            var storedConf = storagePerInterface.get(iface);
            if (!storedConf || !storedConf.id) {
              return false;
            }
            var id = storedConf.id;
            var options = reprefix(storedConf, 'options', name + '[options]');
            options[name + '[_previous_id]'] = id;
            var $options = $('> .ock-depending-element-container', $drilldown);
            $options.empty();
            insertHiddenInputs($options, options);
            $select.val(id).change();
            return false;
          });

          if (!storagePerInterface.get(iface)) {
            $paste.hide();
          }

          storagePerInterface.onChange(iface, function(storedConf) {
            if (storedConf) {
              $paste.show();
            }
            else {
              $paste.hide();
            }
          });
        });
      });
    }
  };

})(jQuery);
