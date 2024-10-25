jQuery(document).ready(function ($) {
  console.log("Custom Template Widget loaded.");

  function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
  }

  // Function to handle AJAX request
  function fireAjaxRequest(newParamValue) {
    console.log("New parameter value:", newParamValue);

    jQuery.ajax({
      url: myAjax.ajaxurl, // Use the localized ajaxurl
      type: "POST",
      data: {
        action: "load_elementor_template_content", // Define this action in your PHP
        post_id: newParamValue,
      },
      success: function (response) {
        // Update the content of the section dynamically
        console.log("AJAX response:", response);
        // jQuery("#elementor-template-section").html(response);
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  // Detect URL parameter change and fire AJAX request
  window.onpopstate = function (event) {
    const newParamValue = getUrlParameter("pid"); // Replace 'my_param' with your actual parameter
    if (newParamValue) {
      fireAjaxRequest(newParamValue); // Fire AJAX request with new parameter value
    }
  };

  // Custom logic for the template widget
  $(".custom-template-wrapper").each(function () {
    console.log("This is the custom template wrapper:", $(this));
    // Add any other JavaScript logic specific to this widget
  });

  function addUrlParameter(name, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(name, value);
    history.pushState(null, "", url); // Update the URL
    return url.searchParams.get(name);
  }

  // Event listener for the Elementor button click
  jQuery(document).ready(function ($) {
    $(document).on(
      "click",
      "#elementor-posts-actions .e-con-inner",
      function (e) {
        e.preventDefault();
        console.log("Elementor button clicked.");

        const pidValue = "947"; // Replace with the actual pid you want to add

        // Add pid to the URL
        const newPid = addUrlParameter("pid", pidValue);

        // Fire the AJAX request with the new pid
        fireAjaxRequest(newPid);
      }
    );
  });
});
