<?php

/*
Plugin Name: PushWa - WaCommerce
Plugin URI: https://PushWa.com
Description: PushWa runs with the woocommerce plugin, to send notification messages to customer numbers,.
Version: 1.0
Author: PushWa.com
*/

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    define('PUSHWA_PATH', plugin_dir_path(__FILE__));
    include('function.php');
} else {
    add_action('admin_notices', 'pushwa_check_woocommerce');
}
