<?php
namespace Elementor\UserTracker;

class API {
    private $db;

    public function __construct() {
        $this->db = Database::get_instance();
        $this->register_routes();
    }

    public function register_routes() {
        add_action('rest_api_init', function() {
            register_rest_route('elementor-tracker/v1', '/users', [
                'methods' => 'GET',
                'callback' => [$this, 'get_users'],
                'permission_callback' => function() {
                    return wp_verify_nonce(
                        $_SERVER['HTTP_X_WP_NONCE'] ?? '', 
                        'wp_rest'
                    );
                },
            ]);

            register_rest_route('elementor-tracker/v1', '/user/(?P<id>\d+)', [
                'methods' => 'GET',
                'callback' => [$this, 'get_user_details'],
                'permission_callback' => [$this, 'check_permissions']
            ]);

            register_rest_route('elementor-tracker/v1', '/user/(?P<id>\d+)/activity', [
                'methods' => 'POST',
                'callback' => [$this, 'update_activity'],
                'permission_callback' => function() {
                    return wp_verify_nonce(
                        $_SERVER['HTTP_X_WP_NONCE'] ?? '', 
                        'wp_rest'
                    );
                },
            ]);

            register_rest_route('elementor-tracker/v1', '/user/(?P<id>\d+)/offline', [
                'methods' => 'POST',
                'callback' => [$this, 'mark_offline'],
                'permission_callback' => function() {
                    return wp_verify_nonce(
                        $_SERVER['HTTP_X_WP_NONCE'] ?? '', 
                        'wp_rest'
                    );
                },
            ]);

            register_rest_route('elementor-tracker/v1', '/login', [
                'methods' => 'POST',
                'callback' => [$this, 'login'],
                'permission_callback' => '__return_true',
                'args' => [
                    'identifier' => [
                        'required' => true,
                        'type' => 'string',
                    ],
                    'password' => [
                        'required' => true,
                        'type' => 'string',
                    ],
                ],
            ]);
        });
    }

    public function check_permissions() {
        return true; // Implement proper authentication
    }

    public function get_users() {
        return new \WP_REST_Response($this->db->get_active_users(), 200);
    }

    public function get_user_details($request) {
        $user_id = $request['id'];
        $user = get_user_by('id', $user_id);
    
        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }
    
        // Get IP address from user meta or server
        $ip_address = get_user_meta($user_id, 'ip_address', true) ?: $_SERVER['REMOTE_ADDR'];
    
        // Get WooCommerce customer data if available
        global $wpdb;
        $customer_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wc_customer_lookup 
            WHERE user_id = %d",
            $user_id
        ));

        $response_data = [
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'name' => $user->display_name,
            'entrance_time' => get_user_meta($user_id, 'entrance_time', true),
            'last_update' => get_user_meta($user_id, 'last_update', true),
            'ip_address' => $ip_address,
            'user_agent' => get_user_meta($user_id, 'user_agent', true),
            'visits_count' => (int)get_user_meta($user_id, 'visits_count', true)
        ];

        // Add WooCommerce data if customer exists
        if ($customer_data) {
            $response_data['woocommerce'] = [
                'first_name' => $customer_data->first_name,
                'last_name' => $customer_data->last_name,
                'city' => $customer_data->city,
                'country' => $customer_data->country,
                'postcode' => $customer_data->postcode,
                'date_last_active' => $customer_data->date_last_active,
                'date_registered' => $customer_data->date_registered,
                'total_spent' => $customer_data->total_spent,
                'orders_count' => $customer_data->orders_count,
            ];
        }

        return new \WP_REST_Response($response_data, 200);
    }

    public function update_activity($request) {
        $user_id = $request['id'];
        
        // Validate user exists
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        $this->db->update_user_activity($user_id);
        
        return new \WP_REST_Response([
            'success' => true,
            'last_update' => get_user_meta($user_id, 'last_update', true)
        ], 200);
    }

    public function login($request) {
        $identifier = sanitize_text_field($request['identifier']);
        $password = sanitize_text_field($request['password']);

        if (empty($identifier) || empty($password)) {
            return new \WP_Error(
                'missing_fields',
                'Please provide both identifier and password',
                ['status' => 400]
            );
        }

        // Try to get user by email first
        $user = get_user_by('email', $identifier);
        
        // If not found by email, try username
        if (!$user) {
            $user = get_user_by('login', $identifier);
        }

        if (!$user) {
            return new \WP_REST_Response([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Verify password
        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            return new \WP_REST_Response([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Trigger wp_login action to ensure all login tracking occurs
        do_action('wp_login', $user->user_login, $user);
        
        // Update user activity
        $this->db->update_user_activity($user->ID);

        // Generate nonce for future requests
        $nonce = wp_create_nonce('wp_rest');

        // Generate and return user data
        return new \WP_REST_Response([
            'success' => true,
            'user' => [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name,
                'entrance_time' => get_user_meta($user->ID, 'entrance_time', true),
                'last_update' => get_user_meta($user->ID, 'last_update', true),
                'visits_count' => (int)get_user_meta($user->ID, 'visits_count', true),
                'nonce' => $nonce
            ]
        ], 200);
    }
}