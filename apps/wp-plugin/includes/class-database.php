<?php
namespace Elementor\UserTracker;

class Database {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_active_users() {
        global $wpdb;

        $threshold = time() - 10;

        return $wpdb->get_results($wpdb->prepare("
            SELECT 
                u.ID as id,
                u.user_login as username,
                u.user_email as email,
                u.display_name as name,
                um1.meta_value as entrance_time,
                um2.meta_value as last_update,
                um3.meta_value as ip_address,
                um4.meta_value as user_agent,
                um5.meta_value as visits_count,
                CASE 
                    WHEN um2.meta_value > %d THEN 'online'
                    ELSE 'offline'
                END as status
            FROM {$wpdb->users} u
            LEFT JOIN {$wpdb->usermeta} um1 ON u.ID = um1.user_id AND um1.meta_key = 'entrance_time'
            LEFT JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id AND um2.meta_key = 'last_update'
            LEFT JOIN {$wpdb->usermeta} um3 ON u.ID = um3.user_id AND um3.meta_key = 'ip_address'
            LEFT JOIN {$wpdb->usermeta} um4 ON u.ID = um4.user_id AND um4.meta_key = 'user_agent'
            LEFT JOIN {$wpdb->usermeta} um5 ON u.ID = um5.user_id AND um5.meta_key = 'visits_count'
            WHERE um2.meta_value IS NOT NULL
            ORDER BY um2.meta_value DESC
        ", $threshold));
    }

    public function update_user_activity($user_id) {
        $current_time = time();
        
        update_user_meta($user_id, 'last_update', $current_time);

        if (!get_user_meta($user_id, 'entrance_time', true)) {
            update_user_meta($user_id, 'entrance_time', $current_time);
            update_user_meta($user_id, 'ip_address', $_SERVER['REMOTE_ADDR']);
            update_user_meta($user_id, 'user_agent', $_SERVER['HTTP_USER_AGENT']);

            $visits = (int)get_user_meta($user_id, 'visits_count', true);
            update_user_meta($user_id, 'visits_count', $visits + 1);
        }

        return [
            'last_update' => $current_time,
            'entrance_time' => get_user_meta($user_id, 'entrance_time', true)
        ];
    }

    public function mark_user_offline($user_id) {
        delete_user_meta($user_id, 'entrance_time');
    }
}