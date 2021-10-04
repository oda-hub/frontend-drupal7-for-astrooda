(function ($) {

Drupal.behaviors.initModalFormsProfile = {
  attach: function (context, settings) {
    $("a[href^='/user/'][href$='/edit'],a[href*='?q=user/'][href$='/edit'],a[href^='/user/'][href*'/edit/'],a[href*='?q=user/'][href*='/edit']", context).once('init-modal-forms-profile', function () {
      this.href = this.href.replace(/user\/([0-9]+)\/edit/,"modal_forms/nojs/user/$1/edit");
    }).addClass('ctools-use-modal ctools-modal-modal-popup-large');
  }
};

})(jQuery);