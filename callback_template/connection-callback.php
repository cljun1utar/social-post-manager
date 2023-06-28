<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card" style="max-width: 500rem;">
                <div class="card-body">
                <div class="row">
                <div class="card-header" for="facebook_app_id" style="margin-bottom:15px;">
                    <h4 style="display:inline-block;float:left;margin-right:15px">Connection Setting</h4>
                    <button type="button" class="btn btn-form-app-Id btn-secondary btn-sm">&#9998; Edit App ID</button>
                    <?php
                        if($app_id != '1234567890' || $app_secret != '1234567890'){
                            echo '<button type="button" class="btn btn-sm btn-success" disabled>Application Status: Connected</button>';
                        }else{
                            echo '<button type="button" class="btn btn-sm btn-danger" disabled>Application Status: Disconnected</button>';
                        }
                    ?>
                    <?php
                        if(isset($_SESSION['long_lived_token'])){
                            echo '<button type="button" class="btn btn-sm btn-success" disabled>Account Status: Connected</button>';
                        }else{
                            echo '<button type="button" class="btn btn-sm btn-danger" disabled>Account Status: Disconnected</button>';
                        }
                    ?>
                    <?php
                        if($pageId != 0){
                            echo '<button type="button" class="btn btn-sm btn-success" disabled>Page Status: Connected</button>';
                        }else{
                            echo '<button type="button" class="btn btn-sm btn-danger" disabled>Page Status: Disconnected</button>';
                        }
                    ?>
                    <?php
                        settings_fields( 'woocommerce_app_id_settings' );
                        $woocommerce_autopost_toggle = get_option( 'woocommerce_autopost_toggle' );
                        if($woocommerce_autopost_toggle == 1){
                            echo '<button type="button" class="btn btn-sm btn-success" disabled>WooCommerce: Connected</button>';
                        }else{
                            echo '<button type="button" class="btn btn-sm btn-warning" disabled>WooCommerce: Disconnected</button>';
                        }
                    ?>
                </div>
                </div>
                <?php
                    if($app_id != '1234567890' || $app_secret != '1234567890'){
                        echo '<form class="form-app-Id" style="display:none;" method="post" action="options.php">';
                    }else{
                        echo '<form class="form-app-Id" method="post" action="options.php">';
                    }
                ?>
                    <?php settings_fields( 'app_id_settings' ); ?>
                    <label style="margin-bottom:20px;" for="facebook_app_id">Facebook App ID:</label>
                    <input type="text" id="facebook_app_id" name="facebook_app_id" value="<?php echo get_option( 'facebook_app_id' ); ?>" class="regular-text">
                    <br>
                    <label for="facebook_app_secret">Facebook App Secret:</label>
                    <input type="text" id="facebook_app_secret" name="facebook_app_secret" value="<?php echo get_option( 'facebook_app_secret' ); ?>" class="regular-text">
                    <div class="row">
                        <div class="col-md-1"><?php submit_button(); ?></div>
                        <div class="col-md-1"><p class="submit"><button type="button" id="reset-facebook-app-id" class="button">Reset My Application</button></p></div>
                    </div>
                </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
if($app_id != '1234567890'){
    if(isset($_SESSION['long_lived_token'])){
        ?>
        <section>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-second text-center">
                    <div class="card-header"><h4>My Profile</h4></div>
                    <div class="card-body text-center">
                        <img src="<?php echo $userInfo['data']['picture']['data']['url'] ?>" alt="Facebook Profile Picture"
                        class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3"><?php echo $userInfo['data']['name'] ?></h5>
                        <p class="text-muted mb-1"><?php echo ucfirst($userInfo['data']['gender']) ?></p>
                        <p class="text-muted mb-4"><?php echo $userInfo['data']['email'] ?></p>
                        <div class="d-flex justify-content-center mb-2">
                        <button type="button" class="btn btn-primary">View Profile</button>
                        <button id="logout-button" type="button" class="btn btn-outline-danger ms-1">Logout</button>     
                    </div>
                    <?php
                        if($pageId){
                            echo '<div style="margin: 25px 0px 25px 0px;" class="card-header"><h4>WooCommerce Set Up</h4></div>';
                            echo '<form class="form_woocommerce_app_id_settings" method="post" action="options.php">';
                            settings_fields( 'woocommerce_app_id_settings' );
                            $woocommerce_autopost_toggle = get_option( 'woocommerce_autopost_toggle' );
                            if($woocommerce_autopost_toggle == 1){
                                echo '<input type="checkbox" hidden id="woocommerce_autopost_toggle" name="woocommerce_autopost_toggle" value="1"' . checked(1, 0, false) . '/>';
                                submit_button('Auto Posting: Enabled', 'primary', 'submit', false, array(
                                    'style' => 'background-color: green;',
                                    'onclick' => "return confirm('Are you sure you want to disable Woocommerce Auto Post?');"
                                ));
                            }else{
                                echo '<input type="checkbox" hidden id="woocommerce_autopost_toggle" name="woocommerce_autopost_toggle" value="1"' . checked(1, 1, false) . '/>';
                                submit_button('Auto Posting: Disabled', 'primary', 'submit', false, array(
                                    'style' => 'background-color: red;',
                                    'onclick' => "return confirm('Are you sure you want to enable Woocommerce Auto Post?');"
                                ));
                            }
                            echo '</form>';
                        }
                    ?> 
                    </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card card-second text-center" style="max-width: 500rem;">
                    <div class="card-header"><h4>Page List</h4></div>
                    <?php
                    foreach($userInfo['data']['accounts']['data'] as $value){
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $value['name'] . '</h5>';
                        echo '<p>'. $value['category'] .'</p>';
                        if($pageId == $value['id']){
                            echo '<a onclick="disconnectPage(this)" class="btn btn-success connect-page" data-page-name="'. $value['name'] .'" data-page-token="'. $value['access_token'] .'" data-page-id="'. $value['id'] .'">Connected</a>';
                        }else{
                            if($pageId && $pageAccessToken){
                                echo '<a onclick="alertPage()" class="btn btn-primary connect-page" data-page-name="'. $value['name'] .'" data-page-token="'. $value['access_token'] .'" data-page-id="'. $value['id'] .'">Connect This Page</a>';
                            }else{
                                echo '<a onclick="connectPage(this)" class="btn btn-primary connect-page" data-page-name="'. $value['name'] .'" data-page-token="'. $value['access_token'] .'" data-page-id="'. $value['id'] .'">Connect This Page</a>';
                            }
                        }
                        echo '<a href="https://facebook.com/' . $value['id'] .'" target="_blank" class="btn btn-secondary ms-1">Visit This Page</a>';
                        echo '</div>';
                        echo '<hr>';
                    }
                    ?>             
                    </div>
                </div>
            </div>
        </section>
        <?php
    }else{
        if($app_id != '1234567890'){
            ?>
                <section>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card text-center">
                            <div class="card-header"><h4>My Profile</h4></div>
                            <div class="card-body text-center">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRayPMv2XCaMVo1fwHqNhLqVhowoMihCSxD7UIPcgUJlQ&s" alt="Blank Profile Picture"
                                class="rounded-circle img-fluid" style="width: 150px;">
                                <h5 class="my-3">You are not connected!</h5>
                                <?php echo '<a href="' . $login_url . '" class="btn btn-success" role="button">Login</a>'; ?>
                                <div class="d-flex justify-content-center mb-2">
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card text-center" style="max-width: 500rem;">
                            <div class="card-header"><h4>Page List</h4></div>
                            <img class="img-fluid mx-auto d-block" src="https://s.tmimgcdn.com/scr/800x500/84100/cute-404-error-specialty-page_84122-original.jpg" alt="Blank Profile Picture" style="width: 400px;height: 250px;">
                            <h5 class="my-3">You are not connected!</h5>
                            </div>
                        </div>
                    </div>
                </section>
            <?php
        }
    }
}
?>
<script>
    $(document).ready(function() {
        $('#wpbody-content').css('padding-bottom','0px');
        var card1 = $('.col-sm-4 .card-second');
        var card2 = $('.col-sm-8 .card-second');
        var maxHeight = Math.max(card1.height(), card2.height());
        card1.height(maxHeight);
        card2.height(maxHeight);
        $(".btn-form-app-Id").click(function() { 
            $(".form-app-Id").toggle();
        });
    });
    function connectPage(data){
        let text = "You will be linking your page";
        if (confirm(text) == true) {
            var page_id = data.getAttribute("data-page-id");
            var page_token = data.getAttribute("data-page-token");
            var page_name = data.getAttribute("data-page-name");
            window.location.href = '<?php global $app_redirect_uri; echo $app_redirect_uri ?>' + '&page_name=' + page_name + '&page_id=' + page_id + '&page_token=' + page_token;
        }
    }
    function disconnectPage(data){
        let text = "Are you sure to disconnect this page?";
        if (confirm(text) == true) {
            var page_id = 0;
            var page_token = 0;
            var page_name = 0;
            window.location.href = '<?php global $app_redirect_uri; echo $app_redirect_uri ?>' + '&page_name=' + page_name + '&page_id=' + page_id + '&page_token=' + page_token;
        }
    }
    function alertPage(){
        alert('Your page still connected, disconnect before further action.');
    }
</script>