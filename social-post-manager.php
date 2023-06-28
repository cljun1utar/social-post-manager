<?php
/*
Plugin Name: Social Post Manager
Plugin URI: http://socialpostmanager.com/
Description: A plugin for managing social media posts.
Version: 1.0
Author: Your Name
Author URI: http://socialpostmanager.com/
License: GPLv2 or later
*/

include plugin_dir_path( __FILE__ ) . 'ajax.php';
include plugin_dir_path( __FILE__ ) . 'functions.php';
include plugin_dir_path( __FILE__ ) . 'settings.php';

require_once 'Facebook/autoload.php';

use Facebook\Facebook;

session_start();

if (isset($_SESSION['long_lived_token'])) {
    $userAccessToken = $_SESSION['long_lived_token'];
    //echo "You are Logged In: ". $access_token;
}else{
    ?>
    <script>
        if (location.href.indexOf("#") > -1 && location.href.indexOf("?page=social-post-manager-connection") > -1) {
            var newUrl = location.href.replace("#", "&");
            location.replace(newUrl);
        }
    </script>
    <?php
    if (isset($_GET['long_lived_token'])) {
        $userAccessToken = $_GET['long_lived_token'];
        $_SESSION['long_lived_token'] = $userAccessToken;
    } else {
        $userAccessToken = 0;
    }
}

if(isset($_SESSION['page_token'])){
    if(isset($_GET['page_token'])){
        if($_GET['page_token'] == 0){
            unset($_SESSION['page_id']);
            unset($_SESSION['page_token']);
            unset($_SESSION['page_name']);
            $pageId = 0;
            $pageAccessToken = 0;
            $pageName = 0;
        }else{
            $pageId = $_SESSION['page_id'];
            $pageAccessToken = $_SESSION['page_token'];
            $pageName = $_SESSION['page_name'];
        }
    }else{
        $pageId = $_SESSION['page_id'];
        $pageAccessToken = $_SESSION['page_token'];
        $pageName = $_SESSION['page_name'];
    }
}else{
    if (isset($_GET['page_token'])) {
        $pageId = $_GET['page_id'];
        $_SESSION['page_id'] = $pageId;
        $pageAccessToken = $_GET['page_token'];
        $_SESSION['page_token'] = $pageAccessToken;
        $pageName = $_GET['page_name'];
        $_SESSION['page_name'] = $pageName;
    } else {
        $pageId = 0;
        $pageAccessToken = 0;
        $pageName = 0;
    }
}

// Your Facebook App ID and App Secret
if( get_option( 'facebook_app_id' ) != null && get_option( 'facebook_app_secret' ) != null){
    $app_id = get_option( 'facebook_app_id' );
    $app_secret = get_option( 'facebook_app_secret' );
}else{
    $app_id = '1234567890';
    $app_secret = '1234567890';
};

//$app_id = '693121725797288';
//$app_secret = '03da5ea242ec8d019b5b6cc275db4ff8';
$app_graph_ver = 'v9.0';
$app_graph_domain = 'https://graph.facebook.com/';

if($_SERVER['HTTP_HOST'] == 'localhost'){
    $app_redirect_uri = 'http://localhost/wordpress/wp-admin/admin.php?page=social-post-manager-connection';
}else{
    $app_redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin.php?page=social-post-manager-connection';
}


if ( isset( $_GET['logout'] ) ) { // log user out
	// clear session
	//unset( $_SESSION );
    $userAccessToken = 0;
	session_destroy();

	// refresh page
	//header( 'get_user_access_token.php' );
	echo '<meta http-equiv="refresh" content="0; URL=' . constant("FB_REDIRECT_URI") . '">';
}

// Create a Facebook object
$fb = new Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => $app_graph_ver,
]);

$userInfoParams = array( // endpoint and params for getting a user
    'endpoint_path'=> 'me',
    'fields' => 'id,name,email,gender,picture.type(large),accounts',
    'access_token' => $userAccessToken,
    'request_type' => 'GET'
);

// get user info from the api
$userInfo = getFacebookUserInfo($userInfoParams);

if(isset($userAccessToken) && $userAccessToken != 0){
    $profile_pic = $userInfo['data']['picture']['data']['url'];
    $profile_name = $userInfo['data']['name'];
}else{
    $profile_pic = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRayPMv2XCaMVo1fwHqNhLqVhowoMihCSxD7UIPcgUJlQ&s';
    $profile_name = 'Not Logged In';
}

$pageInfoParams = array( // endpoint and params for getting page
    'endpoint_path'=> $pageId,
    'fields' => 'access_token,picture.type(large)',
    'access_token' => $userAccessToken,
    'request_type' => 'GET'
);
$pageInfo = getFacebookUserInfo($pageInfoParams);

$pageInsightParams = array( // endpoint and params for getting page
    'endpoint_path'=> $pageId,
    'fields' => 'access_token,picture.type(large)',
    'access_token' => $userAccessToken,
    'request_type' => 'GET'
);
$pageInsight = getFacebookPageInsight($pageInfoParams);

// Add top-level menu
function social_post_manager_menu() {
    add_menu_page(
        'Social Post Manager',
        'Social Post Manager',
        'manage_options',
        'social-post-manager',
        'social_post_manager_overview',
        'dashicons-admin-generic',
        2
    );
}
add_action( 'admin_menu', 'social_post_manager_menu' );

// Add submenu pages
function social_post_manager_submenu() {
    add_submenu_page(
        'social-post-manager',
        'Social Post Manager Overview',
        'Overview',
        'manage_options',
        'social-post-manager',
        'social_post_manager_overview',
    );
    add_submenu_page(
        'social-post-manager',
        'View Posts',
        'View Posts',
        'manage_options',
        'social-post-manager-view-posts',
        'social_post_manager_view_posts',
    );
    add_submenu_page(
        'social-post-manager',
        'Create Post',
        'Create Post',
        'manage_options',
        'social-post-manager-create-post',
        'social_post_manager_create_post'
    );
    add_submenu_page(
        'social-post-manager',
        'Set Up Instruction',
        'Set Up Instruction',
        'manage_options',
        'social-post-manager-manage-posts',
        'social_post_manager_manage_posts',
    );
    add_submenu_page(
        'social-post-manager',
        'Facebook Set Up',
        'Facebook Set Up',
        'manage_options',
        'social-post-manager-connection',
        'social_post_manager_connection',
    );
    add_submenu_page(
        null,
        'Test Function',
        'Test Function',
        'manage_options',
        'social-post-manager-test',
        'social_post_manager_test',
    );
    add_submenu_page(
        null,
        'Edit Social Post',
        'Edit Social Post',
        'manage_options',
        'social-post-manager-edit-posts',
        'social_post_manager_edit_posts'
    );
    add_submenu_page(
        null,
        'View Post - Facebook',
        'View Post - Facebook',
        'manage_options',
        'social-post-manager-view-post-facebook',
        'social_post_manager_view_post_facebook'
    );
}
add_action( 'admin_menu', 'social_post_manager_submenu' );

// Overview callback
function social_post_manager_overview() {
    global $fb, $pageId, $pageAccessToken, $app_graph_domain, $app_graph_ver;
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/overview-callback.php';
}

// Manage Posts callback
function social_post_manager_view_posts() {
    global $fb, $pageId, $pageAccessToken, $app_graph_domain, $app_graph_ver;
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/view-post-callback.php';
}

function social_post_manager_create_post() {
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/create-post-callback.php';
}

// Manage Posts callback
function social_post_manager_manage_posts() {
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/manage-post-callback.php';
}

// Manage Edit Post callback
function social_post_manager_edit_posts() {
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/edit-post-callback.php';
}

// Manage Edit Post callback
function social_post_manager_view_post_facebook() {
    global $profile_pic,$profile_name,$pageName;
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/view-post-facebook-callback.php';
}

// Connections callback
function social_post_manager_connection() {
    global $fb, $app_secret, $app_id, $app_redirect_uri, $userInfo, $pageId, $pageAccessToken, $userAccessToken;
    $state = rand();
    $permission = 'public_profile,email,user_gender,pages_show_list,pages_manage_posts,pages_read_engagement,read_insights,pages_manage_metadata';
    $login_url = 'https://www.facebook.com/v8.0/dialog/oauth?client_id='.$app_id.'&redirect_uri='.$app_redirect_uri.'&scope='.$permission.'&response_type=token&state='.$state;
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/connection-callback.php';
}

// Test callback
function social_post_manager_test() {
    global $fb, $userAccessToken, $pageId, $userInfo, $pageInfo;
    echo '<h1>Testing</h1>';
    require_once plugin_dir_path( __FILE__ ) . 'callback_template/test-callback.php';
}