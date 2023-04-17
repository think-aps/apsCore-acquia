(function ($) {
  $(document).ready(function() {
    $(document).on("click", "span.addevent-button" , function() {
      if ($(this).hasClass("open")) { var open = true; }
      $("span.addevent-button.open").removeClass("open");

      if (!open) {
        $($(this)).addClass("open");
      }
    });

    $(document).on("click", "span.addevent-button ~ ul, span.addevent-button ~ ul li", function() {
      $("span.addevent-button.open").removeClass("open");
    });
  });
}(jQuery));
