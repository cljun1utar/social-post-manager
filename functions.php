<?php

add_filter( 'admin_footer_text', '__return_empty_string', 11 ); 
add_filter( 'update_footer',     '__return_empty_string', 11 );

function my_bootstrap_plugin_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'bootstrap/css/bootstrap.min.css', array(), '5.3.0-alpha1' );

    // Enqueue Bootstrap JS
    wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'bootstrap/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0-alpha1', true );
}
add_action( 'wp_enqueue_scripts', 'my_bootstrap_plugin_scripts' );
add_action( 'admin_enqueue_scripts', 'my_bootstrap_plugin_scripts' );

function load_js() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.2.1.min.js' );
    wp_enqueue_script( 'jquery' );
    wp_deregister_script( 'chartjs' );
    wp_register_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js' );
    wp_enqueue_script( 'chartjs' );
}
add_action('wp_enqueue_scripts','load_js');
add_action('admin_enqueue_scripts','load_js');

function load_cs(){
    wp_deregister_style( 'custom-css' );
    wp_register_style( 'custom-css' , plugin_dir_url( __FILE__ ) . 'style.css' );
    wp_enqueue_style( 'custom-css' );
}
add_action('admin_enqueue_scripts','load_cs');

function load_my_javascript() {
    wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . 'javascript.js', array('jquery') );
}
add_action( 'admin_enqueue_scripts', 'load_my_javascript' );

function getFacebookUserInfo( $params ) {
    global $app_id, $app_secret, $app_graph_ver, $app_graph_domain, $app_redirect_uri;
    // endpoint for getting an access token with code
    $endpoint = $app_graph_domain . $app_graph_ver . '/' . $params['endpoint_path'];

    $endpointParams = array( // params for the endpoint
        'fields' => $params['fields'],
        'access_token' => $params['access_token']
    );

    // make the api call
    return makeApiCall( $endpoint, $params['request_type'], $endpointParams );
}

function getFacebookPageInsight( $params ) {
    global $app_id, $app_secret, $app_graph_ver, $app_graph_domain, $app_redirect_uri;
    // endpoint for getting an access token with code
    $endpoint = $app_graph_domain . $app_graph_ver . '/' . $params['endpoint_path'] . '/' . 'insights';

    $endpointParams = array( // params for the endpoint
        'fields' => $params['fields'],
        'access_token' => $params['access_token']
    );

    // make the api call
    return makeApiCall( $endpoint, $params['request_type'], $endpointParams );
}

function makeApiCall( $endpoint, $type, $params ) {
    // initialize curl
    $ch = curl_init();

    // create endpoint with params
    $apiEndpoint = $endpoint . '?' . http_build_query( $params );
    
    // set other curl options
    curl_setopt( $ch, CURLOPT_URL, $apiEndpoint );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    // get response
    $response = curl_exec( $ch );

    // close curl
    curl_close( $ch );

    return array( // return data
        'type' => $type,
        'endpoint' => $endpoint,
        'params' => $params,
        'api_endpoint' => $apiEndpoint,
        'data' => json_decode( $response, true )
    );
}

function curlApiCall($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $data = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($data, true);
    return 'Number of post reach in the past 30 days: ' . $result['data'][0]['values'][0]['value'];
}

function ajax_post_test(){
?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#testButton').click(function(){
                var post_message = 'Thisisaannouncement ,greatday!';
                var access_token = 'EAAJ2YZCpAk6gBAOR7ng8UkeD0lMEzY07pQEa5ujogZBfxJRCNH5wJ2mrPEYJ7YqlYzN5pUcLxykyCoVLxbYE1T40xLsvPYV8ydxHMUyVwaOO3JagpHrwd0KSQqvF22hL6ywbZBWudEngnzA5ngO1zjL7X1iHQgcmxoOirqUpIi9YMuX0dSo';
                jQuery.ajax({
                    type : 'POST',
                    url : '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType : 'json',
                    data : {
                        'action': 'action_post_test',  // your action name
                        'f_message': post_message,
                        'f_access_token': access_token,
                    },
                    success: function(data){
                        console.log('Success!');
                    },
                    error: function(data){
                        console.log('Error!');
                        console.log(data);
                    }
                }).done(function(response){
                    alert('Data Saved: ' + response.data);
                    console.log(response.data);
                });
            });
        });

    </script>
<?php
}
add_action("admin_footer", "ajax_post_test");

function ajax_destroy_session(){
?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#logout-button').click(function(e){
                e.preventDefault();
                jQuery.ajax({
                    type : 'POST',
                    url : '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType : 'json',
                    data : {
                        'action': 'action_destroy_session',  // your action name
                    },
                    success: function(data){
                        console.log('Succeess!');
                        window.location.href = '<?php global $app_redirect_uri; echo $app_redirect_uri; ?>';
                    },
                    error: function(data){
                        console.log('Error!');
                        console.log(data);
                    }
                }).done(function(response){
                    alert(response.data);
                });
            });
        });

    </script>
<?php
}
add_action("admin_footer", "ajax_destroy_session");

function ajax_reset_appId(){
?>
    <script>
        jQuery(document).ready(function($) {
            $('#reset-facebook-app-id').click(function() {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: { action: 'reset_facebook_app_id' },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    }
                });
            });
        });
    </script>
<?php
}
add_action("admin_footer", "ajax_reset_appId");

function add_facebook_social_id_field() {
    add_meta_box(
      'facebook_social_id_field',
      'Facebook Social ID',
      'display_facebook_social_id_field',
      'post',
      'normal',
      'default'
    );
  }
  add_action('add_meta_boxes', 'add_facebook_social_id_field');
  
  function display_facebook_social_id_field($post) {
    $value = get_post_meta($post->ID, 'facebook_social_id_value', true);
    echo '<label for="facebook_social_id">Facebook Social ID: </label>';
    echo '<input type="text" id="facebook_social_id" name="facebook_social_id" value="' . esc_attr($value) . '" size="25" />';
  }
  
  function save_facebook_social_id_field($post_id) {
    if (array_key_exists('facebook_social_id', $_POST)) {
      update_post_meta(
        $post_id,
        'facebook_social_id_value',
        $_POST['facebook_social_id']
      );
    }
  }
  add_action('save_post', 'save_facebook_social_id_field');

  function update_facebook_social_id_value_meta() {
        if (isset($_POST['update_meta'])) {
            if($_POST['post_id']=="empty"){
                $post_type = 'post';
                $post_title = isset($_POST['post_title']) ? $_POST['post_title'] : '';
                $post_content = isset($_POST['post_content']) ? $_POST['post_content'] : '';
                $post_status = 'publish';
                $post_author = get_current_user_id();
                $post_date = isset($_POST['post_date']) ? $_POST['post_date'] : date('Y-m-d H:i:s');
                $post_data = array(
                    'post_type'    => $post_type,
                    'post_title'   => $post_title,
                    'post_content' => $post_content,
                    'post_status'  => $post_status,
                    'post_author'  => $post_author,
                    'post_date'    => $post_date,
                );
                $post_id = wp_insert_post($post_data);
                $meta_value = sanitize_text_field($_POST['meta_value']);
                update_post_meta($post_id, 'facebook_social_id_value', $meta_value);
            }else{
                $post_id = intval($_POST['post_id']);
                $meta_value = sanitize_text_field($_POST['meta_value']);
                update_post_meta($post_id, 'facebook_social_id_value', $meta_value);
            }
        }

  }
  
  add_action( 'wp_loaded', 'update_facebook_social_id_value_meta' );
  
  function delete_disconnect_social_post(){
    if (isset($_POST['disconnect_post'])) {
        wp_delete_post($_POST['post_id'],TRUE);
        delete_post_meta($_POST['post_id'], 'facebook_social_id_value' );
    }
  }
  add_action( 'wp_loaded', 'delete_disconnect_social_post' );

  function edit_social_post(){
    global $wpdb;
    if(isset($_POST['update_post'])) {
        $post_id = $_POST['post_id'];
        $post_title = $_POST['post_title'] == "" ? "No Title" : $_POST['post_title'];
        $post_author = $_POST['post_author'];
        $post_status = $_POST['post_status'];
        $post_content = $_POST['post_content'];
        $guid = $_POST['post_permalink'];
        $query = "UPDATE {$wpdb->prefix}posts SET post_title='$post_title', post_author='$post_author', post_status='$post_status', post_content='$post_content' WHERE ID=$post_id";
        $social_post_id = get_post_meta($post_id, 'facebook_social_id_value', true);
        global $pageAccessToken;
        $curl = curl_init();
        $facebook_content = $post_title . "\n\n" . $post_content;
        $encodedUrl = rawurlencode($facebook_content);
        $server_url = "https://graph.facebook.com/".$social_post_id."?message=".$encodedUrl."&access_token=".$pageAccessToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $server_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);

        $curlerror = false;

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            $curlerror = true;
        }

        curl_close($ch);

        if ($curlerror) {
            echo '<script>alert("cURL Error");</script>';
          } else {
            $update_result = $wpdb->query($query);
            if ($update_result === false) {
                echo '<script>alert("Update failed. Please try again");</script>';
            } else {
                echo '<script>alert("Update Successful"); window.location.href = "'.admin_url( 'admin.php?page=social-post-manager-view-posts' ).'";</script>';
            }
          }
    }
  }
  add_action( 'wp_loaded', 'edit_social_post' );

  function create_social_post(){
    global $wpdb;
    if(isset($_POST['create_post'])) {
        $post_title = $_POST['post_title'] == "" ? "No Title" : $_POST['post_title'];
        $current_user = wp_get_current_user();
        $post_author = $current_user->ID;
        $post_status = 'publish';
        $post_content = $_POST['post_content'];

        // Create the post object
        $new_post = array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => $post_status,
            'post_author' => $post_author,
        );

        // Insert the post into the database
        $post_id = wp_insert_post($new_post);

        //$social_post_id = get_post_meta($post_id, 'facebook_social_id_value', true);
        global $pageAccessToken,$pageId;
        $curl = curl_init();
        $facebook_content = $post_title . "\n\n" . $post_content;
        $encodedUrl = rawurlencode($facebook_content);
        $server_url = "https://graph.facebook.com/".$pageId."/feed?message=".$encodedUrl."&access_token=".$pageAccessToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $server_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);
        $social_post_id = $result;
        update_post_meta(
            $post_id,
            'facebook_social_id_value',
            json_decode($social_post_id)->id
        );

        $curlerror = false;

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            $curlerror = true;
        }

        curl_close($ch);

        if ($curlerror) {
            echo '<script>alert("cURL Error");</script>';
          } else {
            if ($post_id === false) {
                echo '<script>alert("Update failed. Please try again");</script>';
            } else {
                echo '<script>alert("Update Successful"); window.location.href = "'.admin_url( 'admin.php?page=social-post-manager-view-posts' ).'";</script>';
            }
          }
    }
  }
  add_action( 'wp_loaded', 'create_social_post' );

  $woocommerce_autopost_toggle = get_option( 'woocommerce_autopost_toggle' );
  if($woocommerce_autopost_toggle == 1){
    function add_confirm_alert() {
        global $pagenow;
        if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && get_post_type() == 'product' ) {
            $productId = get_the_ID(); // get the product ID
            $productName = get_the_title($productId);
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('#publish').on('click', function() {
                        if (!confirm('Do you wish to publish this product?')) {
                            return false;
                        }else{
                            if(!confirm('Do you wish to post this to your linked social media?')){
                                alert("Posted without link! ID:"+<?php echo $productId; ?>);
                            }else{
                                alert("Posted with link!"+<?php echo $productId; ?>);
                                var productId = <?php echo $productId; ?>;
                                var productName = <?php echo $productName; ?>;
                                var postTitle = 'New product: ' + productName;
                                jQuery.ajax({
                                type : 'POST',
                                url : '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType : 'json',
                                data : {
                                    'action': 'action_post_woocommerce',  // your action name
                                    'f_title': postTitle,
                                    'f_id' : productId
                                },
                                success: function(data){
                                    console.log('Success!');
                                    console.log(data.response);
                                    alert(data);
                                },
                                error: function(data){
                                    console.log('Error!');
                                    console.log(JSON.stringify(data));
                                    alert(data);
                                }
                                }).done(function(response){
                                    //alert('Data Saved: ' + response.data);
                                    console.log(response.response);
                                    alert(response.response);
                                });
                            }
                        }
                    });
                });
            </script>
            <?php
        }
    }
    add_action('admin_footer', 'add_confirm_alert');

    // Define new columns
    function woocommerce_set_socmed_column($columns) {
        $columns['product_socmed_post'] = __('Post Facebook', 'cs-text'); // Instewad of Season use your own value you want to show as a column title
    
        return $columns;
    }
    add_filter( 'manage_product_posts_columns', 'woocommerce_set_socmed_column');
    
    // Show custom field in a new column
    function woocommerce_socmed_column( $column, $post_id ) {
    
        switch ( $column ) {
            case 'product_socmed_post' : // This has to match to the defined column in function above
                // Display a button that will store the product ID and name
                ?>
                <button class="btn btn-primary btn-sm product_socmed_post_trigger" data-product-id="<?php echo $post_id; ?>" data-product-name="<?php echo get_the_title($post_id); ?>">Publish Now</button>
                <?php
                echo get_permalink(12);
                break;
        }
        
    }
    add_action( 'manage_product_posts_custom_column' , 'woocommerce_socmed_column', 10, 2 );

    function ajax_post_woocommerce(){
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $('.product_socmed_post_trigger').click(function(){
                        var productId = jQuery(this).data('product-id');
                        var productName = jQuery(this).data('product-name');
                        var postTitle = 'New product: ' + productName;
                        jQuery.ajax({
                            type : 'POST',
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            dataType : 'json',
                            data : {
                                'action': 'action_post_woocommerce',  // your action name
                                'f_title': postTitle,
                                'f_id' : productId
                            },
                            success: function(data){
                                console.log('Success!');
                                console.log(data.response);
                                alert(data);
                            },
                            error: function(data){
                                console.log('Error!');
                                console.log(JSON.stringify(data));
                                alert(data);
                            }
                        }).done(function(response){
                            //alert('Data Saved: ' + response.data);
                            console.log(response.response);
                            alert(response.response);
                        });
                    });
                });
        
            </script>
        <?php
        }
        add_action("admin_footer", "ajax_post_woocommerce");

  }