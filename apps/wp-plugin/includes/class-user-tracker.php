<?php
namespace Elementor\UserTracker;

class User_Tracker {
    private static $instance = null;
    private $api;
    private $db;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->db = Database::get_instance();
        $this->api = new API();

        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_tracking_script']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_tracking_script']);
        add_action('wp_ajax_update_user_activity', [$this, 'handle_activity_update']);
        add_action('wp_ajax_user_offline', [$this, 'handle_user_offline']);
    }

    public function init() {
        // Check WooCommerce dependency
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>Elementor User Tracker requires WooCommerce to be installed and active.</p></div>';
            });
            return;
        }
    }

    public function enqueue_tracking_script() {
        if (!is_user_logged_in()) return;

        // Get the correct plugin directory URL
        $plugin_url = plugin_dir_url(dirname(__FILE__));
        
        // Debug output to verify path
        error_log('Plugin URL: ' . $plugin_url . 'assets/js/user-tracker.js');

        wp_enqueue_script(
            'user-tracker', 
            $plugin_url . 'assets/js/user-tracker.js', 
            ['jquery'],
            '1.0.0', 
            true
        );
        
        wp_localize_script('user-tracker', 'userTrackerData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('user_tracker_nonce'),
            'updateInterval' => 3000 // 3 seconds
        ]);
    }

    public function handle_activity_update() {
        $this->verify_nonce();

        $user_id = get_current_user_id();
        if ($user_id) {
            $this->db->update_user_activity($user_id);
            wp_send_json_success();
        }
        wp_send_json_error('User not logged in');
        wp_die();
    }

    public function handle_user_offline() {
        $this->verify_nonce();

        $user_id = get_current_user_id();
        if ($user_id) {
            $this->db->mark_user_offline($user_id);
            wp_send_json_success();
        }
        wp_send_json_error('User not logged in');
        wp_die();
    }

    public function verify_nonce() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'user_tracker_nonce')) {
            wp_send_json_error('Invalid nonce');
            wp_die();
        }
    }
}