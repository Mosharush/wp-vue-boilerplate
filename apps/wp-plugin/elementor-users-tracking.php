<?php
/**
 * Plugin Name: Elementor Users Tracking
 * Description: Live user tracking plugin
 * Version: 1.0.0
 * Author: Moshe Harush
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0.0
 * WC tested up to: 8.7.0
 * 
 * @package Elementor\UserTracker
 * @author Moshe Harush <moshe@deploy.co.il>
 * @version 1.0.0
 */


defined('ABSPATH') || exit;

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function() {
        ?>
        <div class="error">
            <p><?php _e('Elementor Users Tracking requires WooCommerce to be installed and active.', 'elementor-users-tracking'); ?></p>
        </div>
        <?php
    });
    return;
}

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'Elementor\\UserTracker\\';
    $base_dir = plugin_dir_path(__FILE__) . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('\\', '-', $relative_class)) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

class Elementor_User_Tracker {
    private static $instance = null;
    private $refresh_interval = 3; // seconds
    private $api;
    private $db;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_components();
        $this->init_hooks();
    }

    private function init_components() {
        $this->db = new Elementor\UserTracker\Database();
        $this->api = new Elementor\UserTracker\API();
    }
    
    private function init_hooks() {
        add_action('init', [$this, 'handle_cors']);
        add_action('wp_login', [$this, 'track_user_login'], 10, 2);
    }
    
    public function handle_cors() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }
    
    public function track_user_login($user_login, $user) {
        $current_time = time();
        update_user_meta($user->ID, 'entrance_time', $current_time);
        update_user_meta($user->ID, 'last_update', $current_time);
        update_user_meta($user->ID, 'ip_address', $_SERVER['REMOTE_ADDR']);
        update_user_meta($user->ID, 'user_agent', $_SERVER['HTTP_USER_AGENT']);
        
        $visits = (int)get_user_meta($user->ID, 'visits_count', true);
        update_user_meta($user->ID, 'visits_count', $visits + 1);
    }
    
    private function generate_token($user) {
        return base64_encode(json_encode([
            'user_id' => $user->ID,
            'email' => $user->user_email,
            'exp' => time() + (7 * DAY_IN_SECONDS)
        ]));
    }
}

// Initialize plugin
add_action('plugins_loaded', function() {
    Elementor_User_Tracker::get_instance();
});