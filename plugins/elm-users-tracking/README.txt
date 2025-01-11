# Selectika WC
Contributors: selectika, localghost-il
Tags: selectika, woocommerce, web component, shortcode
Requires at least: 4.8.13
Tested up to: 6.5.3
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

This is a WordPress plugin that adds a shortcode for the Selectika WC web component and its script.

## Features

-   Adds a `[selectika]` shortcode.
-   Adds a settings page for the plugin in the WordPress admin area.
-   Allows setting default values for the shortcode attributes.
-   Allows selecting which action types to use.
-   Auto-injects widgets into the end of product pages, cart, checkout based on the settings.

## Installation

1. Download the plugin files.
2. Upload the plugin files to your `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Use the `Settings -> Selectika WC` screen to configure the plugin.

## Usage

Use the `[selectika]` shortcode in your posts or pages. The shortcode accepts the following attributes:

-   `action`: The action type. Default value can be set in the plugin settings page. Possible values are 'sbi', 'ctl', 'rdc', 'sps', 'stl', 'fit', 'ffs', 'tag'.
-   `item`: The item identifier. This should be provided when using the shortcode.
-   `retailer`: The retailer identifier. Default value can be set in the plugin settings page.
-   `lang`: The language code. Default value can be set in the plugin settings page.

Example usage of the shortcode with attributes:

```markdown
[selectika action="sbi" item="123" retailer="myStore" lang="en-US"]
```

You can set the default values for the shortcode attributes in the plugin settings page.

## Options

The options page allows setting the following defaults and configurations:

-   Default Language: Set the default language code.
-   Default Retailer: Set the default retailer identifier.
-   Default Action: Set the default action type.
-   Auto Inject Widgets:
    -   Enable auto-injection of widgets into the end of product pages, cart, and checkout pages.
    -   Select one or multiple action types to auto-inject. Possible action types are:
        -   Selectika By Image (sbi)
        -   Complete The Look (ctl)
        -   Retailer Defined Carousels (rdc)
        -   Selectika Personal Shopper (sps)
        -   Shop The Look (stl)
        -   Fit Clothes (fit)
        -   Fit For Shoes (ffs)
        -   Tagging supervisor (tag)

## License

This project is licensed under the MIT License.
