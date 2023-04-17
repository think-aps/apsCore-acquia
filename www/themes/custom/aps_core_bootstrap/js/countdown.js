(function($, Drupal) {

  'use strict';

  $(document).ready(function() {

    if ($('.countdown-timer .date-fragment.s').length > 0){
      $('.countdown-timer').each(function(index, element) {
        window['countdownTimer' + index] = setInterval(function () {
          countdownTick(index, element);
        }, 1000);
      });
    }
  });

  function countdownTick(index, element) {
    let container = $(element);

    let s = parseInt(container.find('.s span').text());
    let m = parseInt(container.find('.i span').text());
    let h = parseInt(container.find('.h span').text());
    let d = parseInt(container.find('.days span').text());

    s--;
    if (s < 0) {
      s = 59;

      m--;
      if (m < 0) {
        m = 59;

        h--;
        if (h < 0) {
          h = 23;

          d--;
          if (d < 0) {
            d = h = m = s = 0;

            clearInterval(window['countdownTimer' + index]);
            container.find('.date-fragment').remove();
            container.find('.live-event').addClass('active');
          }
        }
      }
    }
    container.find('.s span').text(padString(s, 2));
    container.find('.i span').text(padString(m, 2));
    container.find('.h span').text(padString(h, 2));
    container.find('.days span').text(padString(d, 2));
  }

  function padString (str, max) {
    str = str.toString();
    return str.length < max ? padString("0" + str, max) : str;
  }

})(jQuery, Drupal);
