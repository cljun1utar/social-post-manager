<?php

function action_post_test(){
    $response = array();
    if ( ! empty($_POST['f_message'] ) ) {

        // get user info from the api
        $userInfo = getFacebookUserInfo( $userInfoParams );

        $curl = curl_init();
        $server_url = "https://graph.facebook.com/110649268572124/feed?message=".str_replace(' ','%20',$_POST['f_message'])."&access_token=".$_POST['f_access_token'];
        curl_setopt_array($curl, [
            CURLOPT_URL => $server_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: XYZ",
                "x-rapidapi-key: ABc"
            ],
        ]);
        $response['response'] = curl_exec($curl);
        $response['response'] = 'Received';
        $err = curl_error($curl);
        curl_close($curl);
    } else {
        $response['response'] = 'Not Received';
    }
    wp_send_json_success($_POST['f_message']);
    wp_send_json_error($_POST['f_message']);
}
add_action('wp_ajax_action_post_test', 'action_post_test'); // only for logged-in users

function action_destroy_session() {
    session_start();
    session_destroy();
    wp_send_json_success( 'Session destroyed' );
}
add_action( 'wp_ajax_action_destroy_session', 'action_destroy_session' );

function reset_facebook_app_id() {
    update_option( 'facebook_app_id', '' );
    update_option( 'facebook_app_secret', '' );
    session_start();
    session_destroy();
    echo 'Facebook App ID reset';
    wp_die();
}
add_action( 'wp_ajax_reset_facebook_app_id', 'reset_facebook_app_id' );

function action_post_woocommerce(){
    $response = array();
    if ( ! empty($_POST['f_id'] ) ) {
        global $pageAccessToken,$pageId;
        $post_content = "Check it out here! " . get_permalink($_POST['f_id']);
        $facebook_content = $_POST['f_title'] . "\n\n" . $post_content;
        $encodedUrl = rawurlencode($facebook_content);
        //$product_image_id = get_post_thumbnail_id($_POST['f_id']);
        // if ($product_image_id) {
        //     $imageUrl = wp_get_attachment_url($product_image_id);
        //     $server_url = "https://graph.facebook.com/".$pageId."/photos?url=".$imageUrl."&caption=".$encodedUrl."&access_token=".$pageAccessToken;            
        // } else {
        //     if($_SERVER['HTTP_HOST'] == 'localhost'){
        //         $server_url = "https://graph.facebook.com/".$pageId."/feed?message=".$encodedUrl."&access_token=".$pageAccessToken;
        //     }else{
        //         $server_url = "https://graph.facebook.com/".$pageId."/feed?message=".$encodedUrl."&link=".get_permalink($_POST['f_id'])."&access_token=".$pageAccessToken;
        //     }
        // }
        if($_SERVER['HTTP_HOST'] == 'localhost'){
            $server_url = "https://graph.facebook.com/".$pageId."/feed?message=".$encodedUrl."&access_token=".$pageAccessToken;
        }else{
            $server_url = "https://graph.facebook.com/".$pageId."/feed?message=".$encodedUrl."&link=".get_permalink($_POST['f_id'])."&access_token=".$pageAccessToken;
        }
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $server_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: XYZ",
                "x-rapidapi-key: ABc"
            ],
        ]);

        $response['response'] = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            $response['response'] = "cURL Error #:" . $err;
        } else {
            $response['server_url'] = $server_url;
        }
    } else {
        $response['response'] = 'Not Received';
    }
    echo json_encode($response);
}
add_action('wp_ajax_action_post_woocommerce', 'action_post_woocommerce'); // only for logged-in users