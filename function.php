<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Jakarta');

add_action('admin_notices', 'pushwa_notification');

function pushwa_notification()
{
    $is_admin = current_user_can('manage_options');
    if (is_user_logged_in() && current_user_can('administrator')) {
        if ($is_admin) {
            $class = 'notice notice-success';
            $message = '';
            if (false === get_option('pushwa_token')) {
                $message = sprintf(__('PushWa installed success, <a href="admin.php?page=pushwa-wacommerce">Setup Your Plugin Now.</a>'));
            }

            if ($message) {
                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
            }
        }
    }
}

function pushwa_check_woocommerce()
{
    $is_admin = current_user_can('manage_options');
    if (is_user_logged_in() && current_user_can('administrator')) {
        if ($is_admin) {
            $class = 'notice notice-success';
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), sprintf(__('Your plugin WooCommerce is not active.')));
        }
    }
}



add_action('admin_menu', 'pushwa_admin_menu');
function pushwa_admin_menu()
{
    add_menu_page('PushWa', 'PushWa', 'manage_options', 'pushwa-wacommerce', 'pushwa_menu_setup', 'dashicons-whatsapp', 56);
}

function pushwa_menu_setup()
{
    $is_admin = current_user_can('manage_options');
    if ($is_admin) {
        pushwa_save_settings();
        include PUSHWA_PATH . 'pages/admin-settings.php';
    }
}

add_action('admin_menu', 'pushwa_add_woocommerce_submenu');
function pushwa_add_woocommerce_submenu()
{
    add_submenu_page(
        'woocommerce',
        'PushWa',
        'PushWa',
        'manage_options',
        'pushwa-wacommerce',
        'pushwa_menu_setup'
    );
}

function pushwa_save_settings()
{
    if (is_user_logged_in() && current_user_can('administrator') && isset($_POST['pushwa_token'])) {
        foreach ($_POST as $key => $val) {
            update_option($key, $val);
        }
        pushwa_notice('Save Success');
    }
}

function pushwa_notice($msg = null, $error = false)
{
    echo '
	<div class="notice notice-' . (($error) ? 'error' : 'success') . ' is-dismissible">
		<p>' . $msg . '</p>
	</div>
	';
}

add_filter('woocommerce_billing_fields', 'pushwa_phone_required');

function pushwa_phone_required($fields)
{
    $fields['billing_phone']['required'] = true;
    return $fields;
}


add_action('woocommerce_thankyou_order_id', 'pushwa_thankyou_page');

function pushwa_thankyou_page($order_id)
{
    global $woocommerce, $order, $post;

    $message  = get_option('pushwa_msg_new_order');

    if (!empty($order_id) && empty(get_post_meta($order_id, 'pushwa_notif_new_order', true)) && !empty($message)) {

        $order      = new WC_Order($order_id);
        $currency      = $order->get_currency();
        $payment_method = $order->get_payment_method();
        $payment_title = $order->get_payment_method_title();
        $order_created  = $order->get_date_created()->date('d-m-Y H:i:s');
        $total      = $order->total;

        foreach ($order->get_items() as $item) {
            $produk[] = $item->get_quantity() . "x " . $item->get_name() . "\nSubtotal :  " . wp_strip_all_tags(wc_price($item->get_total()));
        }

        $allItem = implode("\r\n", $produk);
        $payment_method = array();

        if ($payment_method == "bacs") {
            $bacs_info  = get_option('woocommerce_bacs_accounts');

            foreach ($bacs_info as $account) {
                $account_name   = esc_attr(wp_unslash($account['account_name']));
                $bank_name      = esc_attr(wp_unslash($account['bank_name']));
                $account_number = esc_attr($account['account_number']);
                $sort_code      = esc_attr($account['sort_code']);
                $iban_code      = esc_attr($account['iban']);
                $bic_code       = esc_attr($account['bic']);

                if ($account_number != "" and $account_name != "") {
                    $data_pembayaran = $bank_name . "\r\n" . $account_number . "\r\n" . $account_name;
                    array_push($payment_method, $data_pembayaran);
                }
            }
        }
        $arr        = json_decode($order, true);
        $first_name = $arr['billing']['first_name'];
        $last_name  = " " . $arr['billing']['last_name'];
        $full_name  = $first_name . $last_name;
        $wa         = $arr['billing']['phone'];

        if ($wa != null or $wa != "") {

            $message  = str_replace("{detail}", $allItem, $message);
            $message  = str_replace("{payment_method}", implode("\n\n", $payment_method), $message);
            $message  = str_replace("{name}", $full_name, $message);
            $message  = str_replace("{order_id}", $order_id, $message);
            $message  = str_replace("{amount}", wp_strip_all_tags(wc_price($total)), $message);
            $message  = str_replace("{date}", $order_created, $message);
            $message  = str_replace("{payment}", $payment_title, $message);

            $send = pushwa_send_message($wa, $message);
            $_SESSION['notif'] = true;
            add_post_meta($order_id, 'pushwa_notif_order', true);
        }
    }
    return true;
}

function pushwa_send_message($target, $message)
{
    $url = 'https://dash.pushwa.com/api/kirimPesan';

    $body = json_encode(array(
        'token' => get_option('pushwa_token'),
        'target' => $target,
        'type' => 'text',
        'delay' => '1',
        'message' => $message
    ));

    $args = array(
        'body'        => $body,
        'headers'     => array('Content-Type' => 'application/json'),
        'timeout'     => 15,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    } else {
        $response_body = json_decode(wp_remote_retrieve_body($response));
        return $response_body->status;
    }
}

add_action('woocommerce_order_status_changed', 'pushwa_status_change', 99, 3);

function pushwa_status_change($order_id, $old_status, $new_status)
{

    $is_admin = current_user_can('manage_options');
    if (is_user_logged_in() && current_user_can('administrator')) {
        if ($is_admin) {
            $order      = new WC_Order($order_id);
            $arr        = json_decode($order, true);
            $wa         = null;

            $nama   = $arr['billing']['first_name'] . " " . $arr['billing']['last_name'];
            $wa   = $arr['billing']['phone'];

            if (!empty($wa)) {

                //status : completed, processing, pending, on-hold, cancelled, refunded, failed
                if ($new_status == "completed") {
                    $pesan  = get_option("pushwa_msg_completed");
                } elseif ($new_status == "processing") {
                    $pesan  = get_option("pushwa_msg_processing");
                } elseif ($new_status == "cancelled") {
                    $pesan  = get_option("pushwa_msg_cancel");
                } elseif ($new_status == "refunded") {
                    $pesan  = get_option("pushwa_msg_refunded");
                } elseif ($new_status == "failed") {
                    $pesan  = get_option("pushwa_msg_failed");
                } else {
                    return;
                }

                $pesan  = str_replace("{name}", $nama, $pesan);
                $pesan  = str_replace("{order_id}", $order_id, $pesan);

                pushwa_send_message($wa, $pesan);
            }
        }
    }
}
