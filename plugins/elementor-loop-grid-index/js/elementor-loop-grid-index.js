jQuery(document).ready(function ($) {
  $(".elementor-loop-container").each(function () {
    // Loop through each item within the closest elementor-loop-container
    $(this)
      .find('[data-elementor-type="loop-item"]')
      .each(function (index) {
        var ordinalSuffix = function (i) {
          var j = i % 10,
            k = i % 100;
          if (j == 1 && k != 11) {
            return i + "st";
          }
          if (j == 2 && k != 12) {
            return i + "nd";
          }
          if (j == 3 && k != 13) {
            return i + "rd";
          }
          return i + "th";
        };

        // Find the placeholder and replace its content with the correct index
        $(this)
          .find(".loop-index-placeholder")
          .text(ordinalSuffix(index + 1));
      });
  });
});
