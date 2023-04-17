/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  let mobileBreakpoint = 768;
  let tabletBreakpoint = 992;
  let desktopBreakpoint = 1200;

  $(window).on('resize', function(){
    $('.paragraph.paragraph--type--carousel').each(function(index, element) {
      controlsRequired(element);
    });
  });

  $(document).ready(function() {
    $('.paragraph.paragraph--type--carousel').each(function(index, element) {
      controlsRequired(element);

      let carousel = $(element).find('ul.carousel-list').lightSlider({
        item: 4,
        loop: false,
        adaptiveHeight: true,
        slideMove: 1,
        slideMargin: 0,
        pager: false,
        controls: false,
        speed: 600,
        responsive : [
          {
            breakpoint: desktopBreakpoint,
            settings: {
              item: 3,
              slideMove: 1,
            }
          },
          {
            breakpoint: tabletBreakpoint,
            settings: {
              item: 2,
              slideMove: 1,
            }
          },
          {
            breakpoint: mobileBreakpoint,
            settings: {
              item: 1,
              slideMove: 1
            }
          }
        ]
      });

      let prev = $(element).find('.carousel-controls.prev');
      let next = $(element).find('.carousel-controls.next');

      prev.on('click', function () {
        carousel.goToPrevSlide();
      });
      next.on('click', function() {
        carousel.goToNextSlide();
      });

    });
  });

  function controlsRequired(element) {
    let carouselItems = 4;

    if ($(document).width() < desktopBreakpoint) {
      carouselItems = 3;
    }
    if ($(document).width() < tabletBreakpoint) {
      carouselItems = 2;
    }
    if ($(document).width() < mobileBreakpoint) {
      carouselItems = 1;
    }

    if ($(element).find('ul.carousel-list li').length > carouselItems) {
      if (!$(element).find('.carousel-wrapper').hasClass('carousel-active')) {
        $(element).find('.carousel-wrapper').addClass('carousel-active');
      }
    } else {
      if ($(element).find('.carousel-wrapper').hasClass('carousel-active')) {
        $(element).find('.carousel-wrapper').removeClass('carousel-active');
      }
    }
  }

})(jQuery, Drupal);
