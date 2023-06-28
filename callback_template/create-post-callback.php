<h1>Create Post</h1>
<form action="" method="post">
  <div class="form-group">
    <label for="post_title">Post Title</label>
    <input type="text" class="form-control" name="post_title" value="">
  </div>
  
  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea class="form-control" name="post_content" id="" cols="30" rows="10"></textarea>
  </div>
  <button style="margin-top:10px;" id="preview-page" class="btn btn-secondary">Preview Post</button>
  <input style="margin-top:10px;" type="submit" name="create_post" value="Create Post" class="btn btn-primary">
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