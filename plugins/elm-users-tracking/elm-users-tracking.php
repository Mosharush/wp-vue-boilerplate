<?php
/**
 * Plugin Name: Selectika AI for eCommerce
 * Description: A plugin to add a shortcode for the Selectika WC web component and its script.
 * Version: 1.0.0
 * Tags: selectika, web component, fashion, clothing, store, shop, AI, retail, e-commerce, Artificial Intelligence, WooCommerce
 * Author: Selectika Team
 */

define('SLK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SLK_PLUGIN_VERSION', '1.0.0');

// Define constants for action types
define('SLK_ACTION_TYPES', array(
    'sbi' => 'Selectika By Image',
    'ctl' => 'Complete The Look',
    'rdc' => 'Retailer Defined Carousels',
    'sps' => 'Selectika Personal Shopper',
    'stl' => 'Shop The Look',
    'fit' => 'Fit Clothes',
    'ffs' => 'Fit For Shoes',
    'tag' => 'Tagging supervisor',
));

// Shortcode function

if (!function_exists('slk_selectika_wc_shortcode')) {
    function slk_selectika_wc_shortcode($atts)
    {
        global $product;

        // Get options
        $options = get_option('slk_selectika_wc_options');

        // Filter attributes to remove empty values
        $atts = array_filter($atts);

        // Set default attributes with better handling of default values
        $default_atts = array(
            'action' => (!empty($options['default_action']) ? $options['default_action'] : 'sbi'),
            'item' => ($atts['item'] ? '' : ($product ? $product->get_sku() : '')),
            'retailer' => (!empty($options['default_retailer']) ? $options['default_retailer'] : $_SERVER['HTTP_HOST']),
            'lang' => (!empty($options['default_lang']) ? $options['default_lang'] : get_bloginfo('language')),
            'mtop' => '0',
            'mbottom' => '80px',
        );

        // Merge user provided attributes with defaults
        $atts = shortcode_atts($default_atts, $atts, 'selectika');

        // Return the web component
        return sprintf('<div class="slk-wrapper" style="margin-top: %s; margin-bottom: %s;"><selectika-wc action="%s" item="%s" retailer="%s" lang="%s"></selectika-wc></div>',
            esc_attr($atts['mtop']),
            esc_attr($atts['mbottom']),
            esc_attr($atts['action']),
            esc_attr($atts['item']),
            esc_attr($atts['retailer']),
            esc_attr($atts['lang'])
        );
    }
}
add_shortcode('selectika', 'slk_selectika_wc_shortcode');

// Enqueue script function
if (!function_exists('slk_selectika_enqueue_script')) {
    function slk_selectika_enqueue_script()
    {
        wp_enqueue_script('selectika', plugins_url('js/selectika.js', __FILE__), array(), SLK_PLUGIN_VERSION, true);
        wp_enqueue_script('selectika-url-handler', plugins_url('js/url-handler.js', __FILE__), array('selectika'), SLK_PLUGIN_VERSION, true);

        // Localize script with nonce and ajax URL
        wp_localize_script('selectika-url-handler', 'slk_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('slk_reload_widget_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'slk_selectika_enqueue_script');

// Add options page
if (!function_exists('slk_selectika_wc_add_options_page')) {
    function slk_selectika_wc_add_options_page()
    {
        add_options_page(
            'Selectika WC Options',
            'Selectika WC',
            'manage_options',
            'selectika-wc',
            'slk_selectika_wc_options_page_html'
        );
    }
}
add_action('admin_menu', 'slk_selectika_wc_add_options_page');

// Options page HTML
if (!function_exists('slk_selectika_wc_options_page_html')) {
    function slk_selectika_wc_options_page_html() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Show error/update messages
        settings_errors('slk_selectika_wc_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                // Output security fields
                settings_fields('selectika_wc');
                // Output setting sections and their fields
                do_settings_sections('selectika_wc');
                // Output save settings button
                submit_button('Save Settings');
                ?>
            </form>
            <h2>Example Shortcodes</h2>
            <p>Use the following shortcodes in your posts or pages:</p>
            <pre>[selectika action="sbi" item="123" retailer="myStore" lang="en-US"]</pre>
        </div>
        <?php
    }
}

// Register settings
if (!function_exists('slk_selectika_wc_settings_init')) {
    function slk_selectika_wc_settings_init()
    {
        register_setting('selectika_wc', 'slk_selectika_wc_options');

        add_settings_section(
            'slk_selectika_wc_section',
            'Selectika WC Settings',
            '',
            'selectika_wc'
        );

        add_settings_field(
            'slk_selectika_wc_field_lang',
            'Default language',
            'slk_selectika_wc_field_lang_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );

        add_settings_field(
            'slk_selectika_wc_field_retailer',
            'Default retailer',
            'slk_selectika_wc_field_retailer_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );

        add_settings_field(
            'slk_selectika_wc_field_action',
            'Default action',
            'slk_selectika_wc_field_action_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );

        add_settings_field(
            'slk_auto_inject_product',
            'Auto Inject Widgets on Product Page',
            'slk_auto_inject_product_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );

        add_settings_field(
            'slk_auto_inject_cart',
            'Auto Inject Widgets on Cart Page & Widgets',
            'slk_auto_inject_cart_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );

        add_settings_field(
            'slk_auto_inject_checkout',
            'Auto Inject Widgets on Checkout Page',
            'slk_auto_inject_checkout_cb',
            'selectika_wc',
            'slk_selectika_wc_section'
        );
    }
}
add_action('admin_init', 'slk_selectika_wc_settings_init');

// Field callbacks
if (!function_exists('slk_selectika_wc_field_lang_cb')) {
    function slk_selectika_wc_field_lang_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <input type="text" name="slk_selectika_wc_options[default_lang]" value="<?php echo esc_attr($options['default_lang'] ?? ''); ?>">
        <?php
    }

    function slk_selectika_wc_field_retailer_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <input type="text" name="slk_selectika_wc_options[default_retailer]" value="<?php echo esc_attr($options['default_retailer'] ?? ''); ?>">
        <?php
    }

    function slk_selectika_wc_field_action_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <input type="text" name="slk_selectika_wc_options[default_action]" value="<?php echo esc_attr($options['default_action'] ?? ''); ?>">
        <?php
    }

    function slk_auto_inject_product_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <p>Select actions to auto-inject on product page:</p>
        <?php
        foreach (SLK_ACTION_TYPES as $value => $name) {
            ?>
            <label>
                <input type="checkbox" name="slk_selectika_wc_options[auto_inject_product][<?php echo esc_attr($value); ?>]" value="1" <?php checked(1, $options['auto_inject_product'][$value], true); ?>>
                <?php echo esc_html($name); ?>
            </label>
            <br/>
            <?php
        }
    }

    function slk_auto_inject_cart_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <p>Select actions to auto-inject on cart page & widgets:</p>
        <?php
        foreach (SLK_ACTION_TYPES as $value => $name) {
            ?>
            <label>
                <input type="checkbox" name="slk_selectika_wc_options[auto_inject_cart][<?php echo esc_attr($value); ?>]" value="1" <?php checked(1, $options['auto_inject_cart'][$value], true); ?>>
                <?php echo esc_html($name); ?>
            </label>
            <br/>
            <?php
        }
    }

    function slk_auto_inject_checkout_cb() {
        $options = get_option('slk_selectika_wc_options');
        ?>
        <p>Select actions to auto-inject on checkout page:</p>
        <?php
        foreach (SLK_ACTION_TYPES as $value => $name) {
            ?>
            <label>
                <input type="checkbox" name="slk_selectika_wc_options[auto_inject_checkout][<?php echo esc_attr($value); ?>]" value="1" <?php checked(1, $options['auto_inject_checkout'][$value], true); ?>>
                <?php echo esc_html($name); ?>
            </label>
            <br/>
            <?php
        }
    }

    // Auto inject function
    function slk_selectika_wc_auto_inject() {
        $options = get_option('slk_selectika_wc_options');

        if (!empty($options['auto_inject_product'])) {
            add_action('woocommerce_after_single_product_summary', 'slk_selectika_wc_inject_to_product_page', 10);
            // add_action('woocommerce_after_single_product', 'slk_selectika_wc_inject_to_product_page', 0);
        }

        if (!empty($options['auto_inject_cart'])) {
            if (is_cart()) {
                add_action('loop_end', 'slk_selectika_wc_inject_to_cart_page');
            }
            add_action('woocommerce_mini_cart_contents', 'slk_selectika_wc_inject_to_cart_page');
        }

        if (!empty($options['auto_inject_checkout'])) {
            if (is_checkout()) {
                add_action('loop_end', 'slk_selectika_wc_inject_to_checkout_page');
            }
            add_action('woocommerce_after_checkout_registration_form', 'slk_selectika_wc_inject_to_checkout_page');
        }
    }
    add_action('wp', 'slk_selectika_wc_auto_inject');

    function slk_selectika_wc_inject_to_product_page() {
        $options = get_option('slk_selectika_wc_options');
        if (!empty($options['auto_inject_product'])) {
            foreach ($options['auto_inject_product'] as $action => $enabled) {
                if ($enabled) {
                    echo do_shortcode("[selectika action=\"$action\"]");
                }
            }
        }
    }

    function slk_get_last_sku_from_cart() {
        $cartItems = WC()->cart->get_cart();
        $lastItem = end($cartItems);
        $_woo_product = new WC_Product( $lastItem['product_id'] );
        $lastSku = $_woo_product->get_sku();

        return $lastSku;
    }

    function slk_selectika_wc_inject_to_cart_page() {
        $options = get_option('slk_selectika_wc_options');
        $lastSku = slk_get_last_sku_from_cart();

        if (!empty($options['auto_inject_cart'])) {
            foreach ($options['auto_inject_cart'] as $action => $enabled) {
                if ($enabled) {
                    echo do_shortcode("[selectika action=\"$action\" item=\"$lastSku\"]");
                }
            }
        }
    }

    function slk_selectika_wc_inject_to_checkout_page() {
        $options = get_option('slk_selectika_wc_options');
        $lastSku = slk_get_last_sku_from_cart();

        if (!empty($options['auto_inject_checkout'])) {
            foreach ($options['auto_inject_checkout'] as $action => $enabled) {
                if ($enabled) {
                    echo do_shortcode("[selectika action=\"$action\" item=\"$lastSku\"]");
                }
            }
        }
    }

    // AJAX handler to reload widget
    function slk_selectika_wc_reload_widget() {
        check_ajax_referer('slk_reload_widget_nonce', 'security');

        $page_slug = sanitize_text_field($_POST['page_slug']);
        $product_obj = new WC_Product(get_page_by_path( $page_slug, OBJECT, 'product' )->ID);

        if ($product_obj) {
            $sku = $product_obj->get_sku();

            wp_send_json_success(array(
                'sku' => $sku,
            ));
        } else {
            wp_send_json_error(array('message' => 'Page not found.'));
        }
    }
    add_action('wp_ajax_slk_reload_widget', 'slk_selectika_wc_reload_widget');
    add_action('wp_ajax_nopriv_slk_reload_widget', 'slk_selectika_wc_reload_widget');
}
?>
