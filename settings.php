<?php
// Register the new setting
add_action( 'admin_init', 'register_facebook_app_id_setting' );
function register_facebook_app_id_setting() {
    register_setting( 'app_id_settings', 'facebook_app_id' );
}

// Add the new setting field to the General Settings page
//add_filter( 'admin_init' , 'add_facebook_app_id_field' );
function add_facebook_app_id_field() {
    add_settings_field(
        'facebook_app_id',
        'Facebook App ID',
        'facebook_app_id_field_callback',
        'general'
    );
}

// Output the HTML for the setting field
function facebook_app_id_field_callback() {
    $facebook_app_id = get_option( 'facebook_app_id' );
    echo '<input type="text" name="facebook_app_id" value="' . $facebook_app_id . '" class="regular-text" />';
}

// Register the new setting
add_action( 'admin_init', 'register_facebook_app_secret_setting' );
function register_facebook_app_secret_setting() {
    register_setting( 'app_id_settings', 'facebook_app_secret' );
}

// Add the new setting field to the General Settings page
//add_filter( 'admin_init' , 'add_facebook_app_secret_field' );
function add_facebook_app_secret_field() {
    add_settings_field(
        'facebook_app_secret',
        'Facebook App Secret',
        'facebook_app_secret_field_callback',
        'general'
    );
}

// Output the HTML for the setting field
function facebook_app_secret_field_callback() {
    $facebook_app_secret = get_option( 'facebook_app_secret' );
    echo '<input type="text" name="facebook_app_secret" value="' . $facebook_app_secret . '" class="regular-text" />';
}

// Register the new setting
add_action( 'admin_init', 'register_woocommerce_autopost' );
function register_woocommerce_autopost() {
    register_setting( 'woocommerce_app_id_settings', 'woocommerce_autopost_toggle' );
}

// Add the new setting field to the General Settings page
add_filter( 'admin_init' , 'add_woocommerce_autopost_field' );
function add_woocommerce_autopost_field() {
    add_settings_field(
        'woocommerce_autopost_toggle',
        'Activate WooCommerce Autopost',
        'add_woocommerce_autopost_field_callback',
        'general'
    );
}

// Output the HTML for the setting field
function add_woocommerce_autopost_field_callback() {
    $woocommerce_autopost_toggle = get_option( 'woocommerce_autopost_toggle' );
    echo '<input type="checkbox" id="woocommerce_autopost_toggle" name="woocommerce_autopost_toggle" value="1"' . checked(1, $woocommerce_autopost_toggle, false) . '/>';
}