/**
 * @file
 * Global utilities.
 *
 */
import { paragraphCloud } from './word-cloud.js';

(function($, Drupal) {

  'use strict';

  let pollingView;

  $(document).keypress(function(event){
    let keycode = (event.keyCode ? event.keyCode : event.which);

    switch (keycode) {
      case 114: {
        // R = reload
        viewRefresh(pollingView);
        break;
      }
      case 115: {
        // S = show graph
        graphReveal();
        break;
      }
      case 97: {
        // A = show answer
        answerReveal();
        break;
      }
      default: {
        //console.log(keycode);
        break;
      }
    }
  });

  $(document).ready(function() {
    // If the Refresh button is clicked, refresh the view
    $("#polling-refresh").on('click', function (event) {
      if(typeof pollingView !== 'undefined'){
        viewRefresh(pollingView);
      }
    });

    // If the Reveal button is clicked, animate the bars
    $("#polling-animate").on('click', function (event) {
      graphReveal();
    });

    // If the Answer button is clicked, reveal the answers
    $("#polling-answer").on('click', function (event) {
      answerReveal();
    });
  });

  function viewRefresh(view) {
    $(view).trigger('RefreshView');
  }

  function graphReveal() {
    $('.choices .choice').each(function(index, element) {
      let bar = $(this).find('.bar');
      let percentage = $(this).find('.percentage');

      let speed = bar.attr('data-bar-width') * 22.5;

      bar.animate({
        width: bar.attr('data-bar-width') + '%',
      }, {
        duration: speed,
        easing: 'linear',
      });

      percentage.animate({
        opacity: 1,
      }, {
        duration: speed,
        easing: 'linear',
      });
    });

    $('.word-cloud-wrapper').each(function() {
      paragraphCloud($(this).attr('id'));
    });
  }

  function answerReveal() {
    $('.choices .choice').each(function(index, element) {
      let correct = $(this).attr('data-correct');
      let bar = $(this).find('.choice-bar');

      $(this).addClass(correct);
      bar.addClass('animate');

      setTimeout(function () {
        bar.removeClass('animate');
      }, 4000);
    });
  }

  Drupal.behaviors.pollingRefresh = {
    attach: function(context, settings) {
      $.each(Drupal.views.instances, function getInstance(index, element){
        if (element.settings.view_name === 'aps_poll_graph'){
          pollingView = '.js-view-dom-id-' + element.settings.view_dom_id;
        }
      });
    }
  }

})(jQuery, Drupal);
