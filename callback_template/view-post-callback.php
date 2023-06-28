<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card" style="max-width: 500rem;">
                <div class="card-header">
                    <h4 style="display:inline-block;float:left;margin-right:15px">Recent Posts</h4>
                    <input type="text" id="filterInput" onkeyup="filterPost()" placeholder="Search for posts.." title="Type in a post">
                </div>
                <ul id="allPosts" style="padding:0px;">
                <?php
                    $metric = 'posts';
                    $field = 'attachments,message,permalink_url';
                    $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/' . $metric . '?access_token=' . $pageAccessToken;
                    $data = json_decode( wp_remote_get( $url )['body'], true );
                    foreach ($data['data'] as $post) {
                        echo '<li>';
                        echo '<div class="card-body">';
                        echo '<div class="row">';
                        echo '<div class="col-sm-1">';
                        $postURL = $app_graph_domain . $app_graph_ver . '/' . $post['id'] . '?fields=' . $field . '&access_token=' . $pageAccessToken;
                        $postdata = json_decode( wp_remote_get( $postURL )['body'] );
                        if(isset($postdata->attachments)){
                            echo '<img src="' . $postdata->attachments->data[0]->media->image->src . '" alt="Post Picture" style="max-width:75px;">';
                        }else{
                            echo '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/WordPress_blue_logo.svg/1200px-WordPress_blue_logo.svg.png" alt="Post Picture" style="max-width:75px;">';
                        }
                        echo '</div>';
                        echo '<div class="col-sm-5">';
                        $postMessage = $postdata->message;
                        $postParts = explode("\n", $postMessage, 2);
                        $postTitle = isset($postParts[1]) ? $postParts[0] : 'No Title';
                        $postContent = isset($postParts[1]) ? $postParts[1] : $postParts[0];
                        echo '<h5 class="card-title">';
                        echo '<span>' . $postTitle . '</span>';
                        echo '</h5>';
                        echo '<p>';
                        echo '<span class="excerpt">' . $postContent . '</span>';
                        echo '<span class="ellipsis" style="display: none;">...</span>';
                        echo '</p>';
                        echo '<p>'.date('D, d M', strtotime($post['created_time'])).'</p>';
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                'key' => 'facebook_social_id_value',
                                'value' => $postdata->id,
                                ),
                            ),
                        );
                        $posts = new WP_Query($args);
                        while ($posts->have_posts()) {
                            $posts->the_post();
                            $post_id = get_the_ID();
                            $edit_link = get_edit_post_link($post_id);
                            $facebook_social_id = get_post_meta($post_id, 'facebook_social_id_value', true);
                        }
                        if (!empty($facebook_social_id)) {
                            echo '<form action="" method="post" class="disconnectForm">';
                            echo '<span class="badge badge-success" style="background-color:green;">
                            <input type="hidden" name="post_id" value="'.$post_id.'">
                            <input style="background: transparent; border: 0; color: white;" name="disconnect_post" type="submit" value="Connected">
                            </span>';
                            echo '</form>';
                        } else {
                            echo '<span class="badge badge-failure" style="background-color:red;">Not Connected</span>';
                        }
                        echo '</div>';
                        echo '<div class="col-sm-1 text-center">';
                        echo '<h6>Post Reach</h6>';
                        $insightMetric = 'post_impressions,post_engaged_users';
                        $postInsightURL = $app_graph_domain . $app_graph_ver . '/' . $post['id'] . '/insights?metric=' . $insightMetric . '&access_token=' . $pageAccessToken;
                        echo '<h3>0</h3>';
                        echo '</div>';
                        echo '<div class="col-sm-1 text-center">';
                        echo '<h6>Likes</h6>';
                        echo '<h3>0</h3>';
                        echo '</div>';
                        echo '<div class="col-sm-1 text-center">';
                        echo '<h6>Shares</h6>';
                        echo '<h3>0</h3>';
                        echo '</div>';
                        echo '<div class="col-sm-1 text-center">';
                        echo '<h6>Comments</h6>';
                        echo '<h3>0</h3>';
                        echo '</div>';
                        echo '<div class="col-sm-2">';
                        if (!empty($facebook_social_id)) {
                            echo '<a href="'.admin_url( 'admin.php?page=social-post-manager-edit-posts' ). '&edit_post=' . $post_id .'" class="btn btn-primary" role="button" style="width: 100%; margin: 0px 5px 5px 5px;">Edit Post</a>';
                        } else {
                            echo '<form action="" method="post" class="connectForm">';
                            if(!empty($post_id)){
                                echo '<input type="hidden" name="post_id" value="'.$post_id.'">';
                            }else{
                                echo '<input type="hidden" name="post_id" value="empty">';
                            }
                            echo '<input type="hidden" name="meta_value" value="'.$post['id'].'">';
                            echo '<input type="hidden" name="post_title" value="'.$postTitle.'">';
                            echo '<input type="hidden" name="post_content" value="'.$postContent.'">';
                            echo '<input type="hidden" name="post_date" value="'.date('Y-m-d H:i:s', strtotime($post['created_time'])).'">';
                            echo '<input class="btn btn-warning" style="width: 100%; margin: 0px 5px 5px 5px;" type="submit" name="update_meta" value="Connect with Facebook">';
                            echo '</form>';
                        }
                        echo '<a target="_blank" href="'.$postdata->permalink_url.'" class="btn btn-secondary" role="button" style="width: 100%; margin: 5px 5px 0px 5px;">View Post</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<hr>';
                        echo '</li>';
                        unset($post_id);
                        unset($facebook_social_id);
                    }
                ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<script>
var maxLength = 150;
$(".excerpt").each(function() {
    if ($(this).text().length > maxLength) {
        $(this).text($(this).text().substring(0, maxLength));
        $(this).next(".ellipsis").show();
    }
});
$(".connectForm").on('submit', function(){
    return confirm('Do you wish to connect this facebook post in the database?');
})
$(".disconnectForm").on('submit', function(){
    return confirm('Disconnect this post?\nYou will delete the post in the database.');
})
function filterPost() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("filterInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("allPosts");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("span")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
</script>