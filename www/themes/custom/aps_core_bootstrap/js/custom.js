/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  $(document).ready(function() {
    $('ul.nav .dropdown > span').on('click', function () {
      $(this).closest('li').find('ul.dropdown-menu').toggleClass('show');
    });
  });

})(jQuery, Drupal);
