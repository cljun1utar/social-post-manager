<style>
.post-section {
  padding: 20px;
}

.post-header {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.profile-picture {
  height: 50px;
  width: 50px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 20px;
}

.profile-picture img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}

.profile-picture-comment {
  height: 75px;
  width: 75px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 20px;
}

.profile-picture-comment img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}

.profile-name {
  font-size: 20px;
  font-weight: bold;
}

.post-container {
  display: flex;
  flex-direction: column;
  background-color: #f2f2f2;
  padding: 20px;
  border-radius: 10px;
}

.post-text {
  flex: 1;
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 10px;
  margin-bottom: 10px;
  resize: none;
  background-color: white;
  color: black;
}

.post-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.post-media {
  display: none;
}

.post-text-container {
  background-color: white;
  color: black;
  display: flex;
  flex-direction: column;
}
.comments-section {
  width: 90%;
  margin: 0 auto;
}

.comments {
  margin-top: 20px;
}

.comment {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.commenter-profile- picture {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 20px;
}

.comment-content {
  width: 100%;
}

.commenter-name {
  font-weight: bold;
  margin-bottom: 5px;
}

.comment-text {
  margin-bottom: 5px;
}

.comment-time {
  color: #999;
  font-size: 12px;
}

</style>

<div class="post-section">
  <div class="post-container">
    <div class="post-header">
        <div class="profile-picture">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRayPMv2XCaMVo1fwHqNhLqVhowoMihCSxD7UIPcgUJlQ&s" alt="Profile Picture">
        </div>
        <div class="profile-name"><?php echo $pageName; ?></div>
    </div>
    <div class="post-text-container">
        <textarea class="post-text" placeholder="What's on your mind?" cols="30" rows="10" disabled><?php echo $_GET['content'] ?></textarea>
    </div>
    <div class="post-options">
      <input type="file" class="post-media" accept="image/*, video/*">
    </div>
  </div>
</div>
<div class="comments-section">
  <div class="comments">
    <div class="comment">
        <div class="profile-picture-comment">
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture">
        </div>
      <div class="comment-content">
        <h6 class="commenter-name"><?php echo $profile_name; ?></h6>
        <p class="comment-text">See original post at <a href="<?php echo $_GET['permalink']; ?>" target="_blank"><?php echo $_GET['permalink']; ?></a></p>
        <p class="comment-time">3 hours ago</p>
      </div>
    </div>
  </div>
</div>
