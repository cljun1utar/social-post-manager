<section>
    <div class="row">
        <div class="col-sm-4">
            <div class="card" style="max-width: 500rem;">
                <div class="card-header">
                    <h4 style="display:inline-block;float:left;margin-right:15px">Page Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card" style="display: flex;">
                                <h2 style="display:inline-block;">
                                &#127760; 
                                    <?php
                                        $metric = 'page_impressions_unique';
                                        $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&access_token=' . $pageAccessToken;
                                        $data = json_decode( wp_remote_get( $url )['body'] );
                                        echo $data->data[2]->values[1]->value;
                                    ?>
                                </h2>
                                <h5>Post Reach</h5>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card" style="display: flex;">
                                <h2 style="display:inline-block;">
                                &#x1F46A; 
                                    <?php
                                        $metric = 'page_post_engagements';
                                        $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&access_token=' . $pageAccessToken;
                                        $data = json_decode( wp_remote_get( $url )['body'] );
                                        echo $data->data[2]->values[1]->value;
                                    ?>
                                </h2>
                                <h5>Post Engagement</h5>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card" style="display: flex;">
                                <h2 style="display:inline-block;">
                                &#x1F44D; 
                                    <?php
                                        $metric = 'page_fans';
                                        $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&access_token=' . $pageAccessToken;
                                        $data = json_decode( wp_remote_get( $url )['body'] );
                                        echo $data->data[0]->values[1]->value;
                                    ?>
                                </h2>
                                <h5>Page Likes</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card" style="max-width: 500rem;">
                <div class="card-header">
                    <h4 style="display:inline-block;float:left;margin-right:15px">Graph & Discovery</h4>
                    <input type="date" id="start-date">
                    <input type="date" id="end-date">
                </div>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="row">
        <div class="col-sm-12">
            <div class="card" style="max-width: 500rem;">
                <div class="card-header">
                    <h4 style="display:inline-block;float:left;margin-right:15px">Recent Posts</h4>
                    <button type="button" onclick="location.href='<?php echo admin_url('admin.php?page=social-post-manager-view-posts'); ?>'" class="btn btn-sm btn-primary">View All Posts <span class="badge badge-light">
                        <?php
                            $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/posts?&fields=id&access_token=' . $pageAccessToken;
                            $data = json_decode( wp_remote_get( $url )['body'] );
                            echo count($data->data);
                        ?>
                    </span></button>
                </div>
                <?php
                    $metric = 'posts';
                    $field = 'attachments,message,permalink_url';
                    $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/' . $metric . '?access_token=' . $pageAccessToken;
                    $data = json_decode( wp_remote_get( $url )['body'], true );
                    $latest_posts = array_slice($data['data'], 0, 3);
                    foreach ($latest_posts as $post) {
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

<?php
    if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
        $start_date = (string)$_GET['start_date'];
        $end_date = (string)$_GET['end_date'];
    }else{
        $start_date = date("Y-m-d", strtotime("-30 days", strtotime(date("Y-m-d"))));
        $end_date = date("Y-m-d");
    }
?>

document.querySelector("#start-date").value = ("<?php echo $start_date ?>");
document.querySelector("#end-date").value = ("<?php echo $end_date ?>");

document.querySelector("#start-date").addEventListener("change", function() {
    document.querySelector("#end-date").value = "";
});

document.querySelector("#end-date").addEventListener("change", function() {
    let startDate = document.querySelector("#start-date").value;
    let endDate = this.value;
    let currentUrl = window.location.href;
    let newUrl = currentUrl + "&start_date=" + startDate + "&end_date=" + endDate;
    window.location = newUrl;
});

var ctx = document.getElementById('myChart').getContext('2d');
const chartdata_page_impressions_unique = <?php
    $metric = 'page_impressions_unique';
    $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&since='. $start_date .'&until='. $end_date .'&access_token=' . $pageAccessToken;
    $data = json_decode( wp_remote_get( $url )['body'] );
    echo json_encode($data->data[0]->values);
?>;
const chartdata_page_post_engagements = <?php
    $metric = 'page_post_engagements';
    $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&since='. $start_date .'&until='. $end_date .'&access_token=' . $pageAccessToken;
    $data = json_decode( wp_remote_get( $url )['body'] );
    echo json_encode($data->data[0]->values);
?>;
const chartdata_page_fan_adds_unique = <?php
    $metric = 'page_fan_adds_unique';
    $url = $app_graph_domain . $app_graph_ver . '/' . $pageId . '/insights?metric=' . $metric . '&since='. $start_date .'&until='. $end_date .'&access_token=' . $pageAccessToken;
    $data = json_decode( wp_remote_get( $url )['body'] );
    echo json_encode($data->data[0]->values);
?>;
var chartArrayDataX_page_impressions_unique = [];
var chartArrayDataY_page_impressions_unique = [];
var chartArrayDataX_page_post_engagements = [];
var chartArrayDataY_page_post_engagements = [];
var chartArrayDataX_page_fan_adds_unique = [];
var chartArrayDataY_page_fan_adds_unique = [];
var maxValue_array = [];
for (let i = 0; i < chartdata_page_impressions_unique.length; i++) {
    var dateString = chartdata_page_impressions_unique[i]['end_time'];
    var date = new Date(dateString);
    var options = { day: '2-digit', month: 'short' };
    var formattedDate = date.toLocaleDateString('en-US', options);
    chartArrayDataY_page_impressions_unique[i] = chartdata_page_impressions_unique[i]['value'];
    chartArrayDataX_page_impressions_unique[i] = formattedDate;
}
for (let i = 0; i < chartdata_page_post_engagements.length; i++) {
    var dateString = chartdata_page_post_engagements[i]['end_time'];
    var date = new Date(dateString);
    var options = { day: '2-digit', month: 'short' };
    var formattedDate = date.toLocaleDateString('en-US', options);
    chartArrayDataY_page_post_engagements[i] = chartdata_page_post_engagements[i]['value'];
    chartArrayDataX_page_post_engagements[i] = formattedDate;
}
for (let i = 0; i < chartdata_page_fan_adds_unique.length; i++) {
    var dateString = chartdata_page_fan_adds_unique[i]['end_time'];
    var date = new Date(dateString);
    var options = { day: '2-digit', month: 'short' };
    var formattedDate = date.toLocaleDateString('en-US', options);
    chartArrayDataY_page_fan_adds_unique[i] = chartdata_page_fan_adds_unique[i]['value'];
    chartArrayDataX_page_fan_adds_unique[i] = formattedDate;
}

var allArrays = [Array.from(chartArrayDataY_page_impressions_unique), Array.from(chartArrayDataY_page_post_engagements), Array.from(chartArrayDataY_page_fan_adds_unique)];
var highestNumber = Math.max.apply(Math, allArrays.map(function (array) {
    return Math.max.apply(Math, array);
}));
var max = highestNumber + 3;

var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartArrayDataX_page_impressions_unique,
        datasets: [{
            label: 'Page Reach',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: chartArrayDataY_page_impressions_unique,
            fill: false
        }, {
            label: 'Page Engagement',
            backgroundColor: 'rgb(54, 162, 235)',
            borderColor: 'rgb(54, 162, 235)',
            data: chartArrayDataY_page_post_engagements,
            fill: false
        }, {
            label: 'Page Likes',
            backgroundColor: 'rgb(255, 206, 86)',
            borderColor: 'rgb(255, 206, 86)',
            data: chartArrayDataY_page_fan_adds_unique,
            fill: false
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    max: max
                }
            }]
        }
    }
});
</script>
