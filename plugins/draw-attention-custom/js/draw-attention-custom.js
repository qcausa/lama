// Function to get all object titles from the imp-scale container
// function getAllObjectTitles() {
//     var objectTitles = [];
//     jQuery(".imp-scale div[data-title], .imp-scale svg[data-title]").each(
//       function () {
//         objectTitles.push(jQuery(this).data("title"));
//       }
//     );
//     console.log("objectTitles", objectTitles);
//     return objectTitles;
//   }

//   // Function to unhighlight all map points
//   function unhighlightAllPoints(imageName, callback) {
//     var allObjectTitles = getAllObjectTitles();
//     allObjectTitles.forEach(function (objectTitle) {
//       ImageMapPro.unhighlightObject(imageName, objectTitle);
//       console.log("Unhighlighted", objectTitle);
//     });
//     // Add a slight delay to ensure unhighlighting completes
//     setTimeout(callback, 500);
//     console.log("Unhighlighted Complete");
//   }

//   // Function to highlight a specific point
//   function highlightAndFetchContent(chapterName, postId) {
//     console.log("init", postId, chapterName);

//     // Unhighlight all points first, then highlight the specific point after a short delay
//     unhighlightAllPoints("Chapter Map", function () {
//       // Highlight the object
//       ImageMapPro.highlightObject("Chapter Map", chapterName);

//       // Perform the AJAX request
//       jQuery.ajax({
//         url: myAjax.ajaxurl, // Use the localized ajaxurl
//         type: "POST",
//         data: {
//           action: "fetch_elementor_content",
//           post_id: postId,
//         },
//         success: function (response) {
//           jQuery("#elementor-posts-container").html(response); // Assumes this is your container
//         },
//       });
//     });
//   }

// Ensure the DOM is fully loaded before any interactions
jQuery(document).ready(function ($) {
  console.log("DOM is ready");
});

// jQuery.ajax({
//   url: myAjax.ajaxurl, // Use the localized ajaxurl
//   type: "POST",
//   data: {
//     action: "fetch_elementor_content",
//     post_id: 947,
//   },
//   success: function (response) {
//     jQuery("#elementor-posts-container .e-con-inner").html(response); // Assumes this is your container
//   },
// });
jQuery(document).ready(function ($) {
  // Attach a click event to each loop grid item
  $("#elementor-posts-actions .e-loop-item").on("click", function () {
    // Find the class that starts with 'post-' and extract the post ID
    var postClass = $(this)
      .attr("class")
      .match(/post-(\d+)/);
    if (postClass) {
      var postId = postClass[1]; // Extract the post ID

      // Trigger an AJAX request to send the post ID to your PHP function
      $.ajax({
        url: myAjax.ajaxurl, // WordPress AJAX URL (make sure `myAjax.ajaxurl` is localized)
        type: "POST",
        data: {
          action: "fetch_elementor_content", // Your PHP action hook
          post_id: postId, // Pass the post ID
        },
        success: function (response) {
          $("html, body").animate(
            {
              scrollTop: $("#elementor-posts-container").offset().top,
            },
            1000
          ); // Adjust the duration (1000ms = 1 second) as needed
          console.log(
            "Post ID " +
              postId +
              " successfully sent to fetch_elementor_content."
          );

          jQuery("#elementor-posts-container .e-con-inner").html(response); // Assumes this is your container
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    }
  });
});
