/**
 * @file
 * Global utilities.
 *
 */
export function paragraphCloud(id) {
  // https://d3-graph-gallery.com/graph/wordcloud_size.html
  const container = "#" + id;

  if (!jQuery(container + ' svg').length) {
    let max = 1;
    let wordList = [];
    jQuery(container + " .words span").each(function() {
      wordList.push({word: jQuery(this).text(), size: jQuery(this).attr('data-count')});

      max = Math.max(max, jQuery(this).attr('data-count'));
    });

    // set the dimensions and margins of the graph
    let width = jQuery(container).parent().width();
    let height = Math.round(width * (1 / 3));

    // append the svg object to the body of the page
    let svg = d3.select(container).append("svg")
      .attr("width", width)
      .attr("height", height)
      .append("g")
      .attr("transform", "translate(10, 10)");

    // Constructs a new cloud layout instance. It run an algorithm to find the position of words that suits your requirements
    let layout = d3.layout.cloud()
      .size([width, height])
      .words(wordList.map(function(d) { return {text: d.word, size:d.size}; }))
      .rotate(function() { return 0; })
      .padding(10)
      .fontSize(function(d) { return ((parseInt(d.size) / max) * 36) + 12; })
      .on("end", draw);
    layout.start();

    // This function takes the output of 'layout' above and draw the words
    // Better not to touch it. To change parameters, play with the 'layout' variable above
    function draw(words) {
      svg
        .append("g")
        .attr("transform", "translate(" + layout.size()[0] / 2 + "," + layout.size()[1] / 2 + ")")
        .selectAll("text")
        .data(words)
        .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", jQuery('h1').css('font-family'))
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
    }
  }
}

(function($, Drupal) {

  'use strict';

  $(document).ready(function() {
    $('.word-cloud-wrapper').each(function() {
      paragraphCloud($(this).attr('id'));
    });
  });


  function RGBToHex(rgb) {
    // Choose correct separator
    let sep = rgb.indexOf(",") > -1 ? "," : " ";
    // Turn "rgb(r,g,b)" into [r,g,b]
    rgb = rgb.substr(4).split(")")[0].split(sep);

    let r = (+rgb[0]).toString(16),
      g = (+rgb[1]).toString(16),
      b = (+rgb[2]).toString(16);

    if (r.length === 1)
      r = "0" + r;
    if (g.length === 1)
      g = "0" + g;
    if (b.length === 1)
      b = "0" + b;

    return "#" + r + g + b;
  }

  function LightenDarkenColor(col, amt) {
    let usePound = false;

    if (col[0] === "#") {
      col = col.slice(1);
      usePound = true;
    }

    let num = parseInt(col,16);

    let r = (num >> 16) + amt;

    if (r > 255) r = 255;
    else if  (r < 0) r = 0;

    let b = ((num >> 8) & 0x00FF) + amt;

    if (b > 255) b = 255;
    else if  (b < 0) b = 0;

    let g = (num & 0x0000FF) + amt;

    if (g > 255) g = 255;
    else if (g < 0) g = 0;

    return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
  }

})(jQuery, Drupal);
