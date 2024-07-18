/**
 * @file
 * Simple countdown used on the download form.
 */

(function (Drupal) {
  Drupal.emeCountdown = Drupal.emeCountdown || {};
  Drupal.emeCountdown.intervals = Drupal.emeCountdown.intervals || {};

  Drupal.behaviors.emeCountdown = {
    attach: function attach(context) {
      const elements = context.getElementsByClassName('js-eme-countdown');
      if (elements.length) {
        for (let i = 0, max = elements.length; i < max; i++) {
          if (
            elements[i].hasAttribute('data-processed') ||
            Number(parseFloat(elements[i].textContent)).toString() !==
              elements[i].textContent
          ) {
            continue;
          }

          elements[i].setAttribute('data-processed', 'data-processed');
          Drupal.emeCountdown.intervals[i] = setInterval(
            function (element, i) {
              const current = parseInt(element.textContent, 10) - 1;
              element.textContent = current;
              if (current < 1) {
                clearInterval(Drupal.emeCountdown.intervals[i]);
              }
            },
            1000,
            elements[i],
            i,
          );
        }
      }
    },
  };
})(Drupal);
