/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {
  $(document).ready(function () {
    $('.mfp-inline').magnificPopup({
      type: 'inline',
      midClick: true,
      closeOnContentClick: false,
      closeOnBgClick: false,
    });

    $('.mfp-image').magnificPopup({
      type: 'image',
      midClick: true,
      closeOnContentClick: false,
      closeOnBgClick: false
    });

    $('.mfp-iframe').magnificPopup({
      type: 'iframe',
      midClick: true,
      closeOnContentClick: false,
      closeOnBgClick: false
    });

    $('.mfp-custom-close').on( "click", function() {
      $.magnificPopup.close();
    });

    // Stop it from running on the admin pages
    if (!$('body').hasClass('path-admin')) {
      if ($('#apscore-popup').length) {
        $.magnificPopup.open({
          items: {
            src: '#apscore-popup'
          },
          type: 'inline',
          midClick: true,
          closeOnContentClick: false,
          closeOnBgClick: false,
        });
      }
    }
  });
})(jQuery, Drupal);
