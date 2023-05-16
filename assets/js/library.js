jQuery(document).ready(function ($) {

  // Run only if ACF is defined
  if (typeof acf !== "undefined") {

    // Automatically fill the Title field on Image upload with the title defined on WP Media Library
    const imageUploaderCallback = function (field) {
      const imageInput = field.$input();

      // Image changed
      imageInput.on("change", function () {
        const imageTitle = $("#attachment-details-title").val();

        // Get the Title field if we have an image title
        if (imageTitle && imageTitle !== "") {
          const iconTitleField = acf.getFields({
            name: "title",
            sibling: field.$el,
          })[0];

          // Update the Title field if it's empty
          if (iconTitleField && iconTitleField.val() === "") {
            iconTitleField.val(imageTitle);
          }
        }
      });
    };

    // New Row
    acf.addAction(
      "new_field/type=image_aspect_ratio_crop",
      imageUploaderCallback
    );

    // Existing Rows
    acf.addAction(
      "load_field/type=image_aspect_ratio_crop",
      imageUploaderCallback
    );
  }
  
});
