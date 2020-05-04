<form action="<?= WEBROOT ?>/forms/postComment.php" method="post" id="postComment" class="message comment">
  <div class="message-header">
    <input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
    <input type="hidden" name="modelId" value="<?= get('id'); ?>">
    <span>Post a new comment</span>
    <button class="button" name="postComment" value="true" type="submit" form="postComment">Post</button>
  </div>
  <div class="field message-body">
    <div class="control">
      <textarea class="textarea no-hover" name="commentMessage" form="postComment" placeholder="Comment here..." required></textarea>
      </div>
    <span class="help"><?= MARKDOWNSUPPORT ?></span>
    </div>
</form>