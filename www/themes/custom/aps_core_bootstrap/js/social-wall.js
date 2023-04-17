(function($, Drupal) {

  'use strict';

  $(document).ready(function() {
    $('.social-wall-wrapper').each(function(index, element) {
      socialWall(element);

      // Check to see if the element is within a quicktab
      if ($(element).parents('div[class*="-tabpage"]').length > 0) {
        let index = $('div[class*="-tabpage"]').index($(element).parents('div[class*="-tabpage"]'));

        $('ul[class*="-tabs"] li').eq(index).find('a').one('click', function () {
          $('div[class*="-tabpage"]').eq(index).find('.social-wall-cards').masonry();
        });
      }
    });
  });

  function socialWall(element) {
    let container = "#" + $(element).attr('id');

    let wall = $(container + ' .social-wall-cards').imagesLoaded( function() {
      wall.masonry({
        itemSelector: '.social-wall-card-wrapper',
      });
    });

    wall.one('layoutComplete', function() {
      $(container).addClass('sw-ready');
    });
  }

})(jQuery, Drupal);
