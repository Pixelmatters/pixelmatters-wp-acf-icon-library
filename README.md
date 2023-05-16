# ACF Icon Library and Icon Picker Field
Adds an Icon Library ACF Option Page and an 'icon-picker' acf-field.

----

### Please note
Original Plugin, no longer maintained, by: https://github.com/houke/acf-icon-picker

## Description
WordPress plugin that adds an ACF Icon Library and Icon Picker that can be used within other ACF Field Groups:
- Adds a WordPress BO page called "Icon Library" where you can add your Icons;
- Adds an ACF called "Icon Picker" where you can choose from the Icons added to the "Icon Library";
- Supports WPGraphQL and triggers a full build whenever an Icon is added, replaced or deleted;

## Compatibility
This ACF field type is compatible with:
[x] ACF 5
[x] ACF 6

## Required Plugins
- [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)
- [ACF - Image Aspect Ratio Crop](https://wordpress.org/plugins/acf-image-aspect-ratio-crop/)
- [WPGraphQL](https://wordpress.org/plugins/wp-graphql/)
- [WPGraphQL - ACF](https://github.com/wp-graphql/wp-graphql-acf)

## Screenshots
![Icon Library](https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library/blob/main/screenshots/example_icon_library.png)
![Icon Picker](https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library/blob/main/screenshots/example_icon_picker.png)

## Installation

### Via Composer
1. Add a line to your repositories array: `{ "type": "git", "url": "https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library" }`
2. Add a line to your require block: `"pixelmatters/pixelmatters-wp-acf-icon-library": "main"`
3. Run: composer update

### Manually
1. Copy the `px-acf-icon-library` folder into your `wp-content/plugins` folder
2. Activate the Icon Library plugin via the plugins admin page
3. Add your Icons to the Icon Library options page
4. Create a new field via ACF and select the Icon Selector type

## Changelog
* 1.0.0 - First release from Pixelmatters version

## Roadmap
- Automatic title from filename;
- Multiple Icon Upload;
- Multiple Icon Variant Support (Line, Dark, Light);
- Icon Library => Media Library;
- Have ideas for new features? Check below.

## 🤝 How to Contribute
Whether you're helping us fix bugs, improve the docs, or spread the word, thank you! 💪 🧡

Check out our [**Contributing Guide**](https://github.com/Pixelmatters/eslint-config-pixelmatters/blob/main/CONTRIBUTING.md) for ideas on contributing and setup steps.

## :memo: License
Licensed under the [MIT License](./LICENSE).