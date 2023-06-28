<?php
global $wpdb;
if(isset($_GET['edit_post'])){
    $post_id = $_GET['edit_post'];
    $query = "SELECT * FROM {$wpdb->prefix}posts WHERE ID = $post_id";
    $post = $wpdb->get_row($query, ARRAY_A);
    if($post) {
        $post_title = $post['post_title'];
        $post_author = $post['post_author'];
        $post_date = $post['post_date'];
        $post_content = $post['post_content'];
        $post_status = $post['post_status'];
    }
}
?>

<h1>Edit Post</h1>
<form action="" method="post">
  <div class="form-group">
    <label for="post_title">Post Title</label>
    <input type="text" class="form-control" name="post_title" value="<?php echo $post_title == "No Title" ? "" : $post_title; ?>">
  </div>
  
  <div class="form-group">
    <label for="post_author">Post Author</label>
    <select class="form-control" name="post_author">
      <?php
        $authors = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
        foreach ( $authors as $author ) {
          echo '<option value="' . $author->ID . '"';
          if ( $author->ID == $post_author ) echo ' selected';
          echo '>' . $author->display_name . '</option>';
        }
      ?>
    </select>
  </div>

  <div class="form-group">
      <label for="post_status">Post Status</label>
      <select name="post_status" id="post_status" class="form-control">
          <option value="publish" <?php selected( $post_status, 'publish' ); ?>>Published</option>
          <option value="pending" <?php selected( $post_status, 'pending' ); ?>>Pending Review</option>
          <option value="draft" <?php selected( $post_status, 'draft' ); ?>>Draft</option>
          <option value="private" <?php selected( $post_status, 'private' ); ?>>Private</option>
      </select>
  </div>
  
  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo $post_content; ?></textarea>
  </div>
  <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
  <input type="hidden" name="post_permalink" value="<?php echo get_permalink( $post_id ); ?>">
  <button style="margin-top:10px;" id="preview-page" class="btn btn-secondary">Preview Post</button>
  <input style="margin-top:10px;" type="submit" name="update_post" value="Update Post" class="btn btn-primary">
</form>

<script>
  jQuery(document).ready(function($) {
    $('#preview-page').click(function(e){
      e.preventDefault();
      var postTitle = $('input[name="post_title"]').val();
      var postContent = $('textarea[name="post_content"]').val();
      var postPermalink = $('input[name="post_permalink"]').val();
      window.open('admin.php?page=social-post-manager-view-post-facebook&title=' + postTitle + '&permalink=' + postPermalink + '&content=' + postTitle + '%0A%0A' + postContent, '_blank');
    })
  });
</script>