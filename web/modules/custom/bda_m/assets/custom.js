(function (Drupal, $) {
  Drupal.behaviors.redColorInput = {
    attach: function (context, settings) {
      console.log(
        Drupal.theme('message', {
          text: settings.foo
        })
      );
    }
  }
})(Drupal, jQuery);
