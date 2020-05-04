<?php
global $commentor;
global $currentUser;
global $parsedown;
$nameImage = $commentor->getIcon();
?>
<article class="message comment">
  <div class="message-header">
    <span>
      <a href="<?= WEBROOT ?>/Profile?user=<?= $commentor->getDiscordId(); ?>" style="text-decoration:none;">
        <span class="<?= ($commentor->isGay()) ? 'has-text-gay' : ''; ?>"><?= $commentor->getUsername(); ?></span>
        <?php if ($nameImage !== false): ?>
          <figure class="image is-16x16 reset-cursor" style="display:inline-block;">
            <img src="<?= $nameImage['image']; ?>" title="<?= $nameImage['title']; ?>" style="border-radius:0;">
          </figure>
        <?php endif; ?>
      </a>
      <?php if ($commentor->getDiscordId() == $modelAuthorId) { include ROOT . '/resources/components/authorUserTag.php'; } ?>
      <?php foreach ($commentor->getChatRoles() as $tag): ?>
        <span class="tag is-rounded no-hover has-text-white reset-cursor is-borderless <?= $tag['class'] ?>" style="margin-left:1em;"><?= $tag['title'] ?></span>
      <?php endforeach; ?>
    </span>
    <span><?= date('Y-m-d H:i ', $commentId); ?>
        <?php if ($currentUser->isVerified() && ($currentUser->isAdmin() || $currentUser->getDiscordId() == $commentor->getDiscordId())): ?>
        <button class="delete" type="submit" form="deleteComment" name="deleteComment" value="<?= $commentId ?>" title="Delete comment"></button>
        <input type="hidden" form="deleteComment" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
        <input type="hidden" form="deleteComment" name="modelId" value="<?= get('id'); ?>">
      <?php endif; ?>
    </span>
  </div>
  <div class="message-body content">
      <?php foreach (explode("\n", $message) as $line): ?>
        <?= $parsedown->text($line); ?>
      <?php endforeach; ?>
  </div>
</article>
