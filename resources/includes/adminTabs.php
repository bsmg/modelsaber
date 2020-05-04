<!-- Start Tabs -->
<?php
if (empty($getId)) {
  $inputString = 'onclick="this.childNodes[5].checked = true;changeModelType(this.childNodes[5].value);document.getElementById(\'selectionForm\').submit();"';
} else {
  $inputString = 'href="' . $_SERVER['PHP_SELF'] . '"';
}

?>
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="tabs is-centered is-boxed" id="selectionForm">
  <!--<input type="hidden" name="mode" value="fetch">-->
      <ul>
        <li>
          <a class="control has-icons-left">
            <div class="select">
            <select name="status" onchange="changeStatus(this.value);this.form.submit();">
            <?php foreach (STATUSLIST as $option): ?>
              <option value="<?= strtolower($option) ?>" style="color: black;" <?= (strtolower($status) == strtolower($option)) ? 'selected' : ''; ?>><?= ucfirst($option) ?></option>
            <?php endforeach; ?>
          </select>
            </div>
            <?php
              switch ($status) {
                case APPROVED:
                  echo '<span class="icon is-small has-text-success is-left" title="Model has been approved.">
                   <i class="fas fa-check-circle"></i>
                   </span>';
                  break;
                case UNAPPROVED:
                  echo '<span class="icon is-small has-text-warning is-left" title="This model is awaiting approval, give it some time.">
                   <i class="fas fa-exclamation-circle"></i>
                   </span>';
                  break;
                case DECLINED:
                  echo '<span class="icon is-small has-text-danger is-left" title="This model was not approved, ask BSMG Staff why if they haven\'t notified you already">
                   <i class="fas fa-times-circle"></i>
                   </span>';
                  break;
              }
            ?>
          </a>
        </li>
        <li <?= ($modelType == 'all') ? 'class="is-active"' : ''; ?>>
          <a <?= $inputString; ?>>
          <span class="icon is-small"><i class="fas fa-asterisk"></i></span>
          <span>All</span>
          <input type="radio" name="modelType" value="all" class="is-hidden" <?= ($modelType == 'all') ? 'checked' : ''; ?>>
        </a>
        </li>
        <li <?= ($modelType == AVATAR) ? 'class="is-active"' : ''; ?>>
          <a <?= $inputString; ?>>
          <span class="icon is-small"><i class="fas fa-hat-wizard" aria-hidden="true"></i></span>
          <span><?= ucfirst(AVATARS); ?></span>
          <input type="radio" name="modelType" value="<?= AVATAR ?>" class="is-hidden" <?= ($modelType == AVATAR) ? 'checked' : ''; ?>>
        </a>
        </li>
        <li <?= ($modelType == SABER) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small"><i class="fas fa-magic" aria-hidden="true"></i></span>
          <span><?= ucfirst(SABERS); ?></span>
          <input type="radio" name="modelType" value="<?= SABER ?>" class="is-hidden" <?= ($modelType == SABER) ? 'checked' : ''; ?>>
        </a>
        </li>
        <li <?= ($modelType == PLATFORM) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small"><i class="fas fa-torii-gate" aria-hidden="true"></i></span>
          <span><?= ucfirst(PLATFORMS); ?></span>
          <input type="radio" name="modelType" value="<?= PLATFORM ?>" class="is-hidden" <?= ($modelType == PLATFORM) ? 'checked' : ''; ?>>
        </a>
        </li>
        <li <?= ($modelType == NOTE) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/Bloq.svg'; ?></span>
          <span><?= ucfirst(NOTES); ?></span>
          <input type="radio" name="modelType" value="<?= NOTE ?>" class="is-hidden" <?= ($modelType == NOTE) ? 'checked' : ''; ?>>
        </a>
        </li>
        <?php if (false): ?>
        <li <?= ($modelType == TRAIL) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/trail.svg'; ?></span>
          <span><?= ucfirst(TRAILS); ?></span>
          <input type="radio" name="modelType" value="<?= TRAIL ?>" class="is-hidden" <?= ($modelType == TRAIL) ? 'checked' : ''; ?>>
        </a>
        </li>
        <?php endif; ?>
        <?php if (false): ?>
        <li <?= ($modelType == SIGN) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small" style="fill: currentColor;"><?php include ROOT . '/resources/sign.svg'; ?></span>
          <span><?= ucfirst(SIGNS); ?></span>
          <input type="radio" name="modelType" value="<?= SIGN ?>" class="is-hidden" <?= ($modelType == SIGN) ? 'checked' : ''; ?>>
        </a>
        </li>
        <?php endif; ?>
        <li <?= ($modelType == MISC) ? 'class="is-active"' : ''; ?>>
        <a <?= $inputString; ?>>
          <span class="icon is-small"><i class="fas fa-shapes" aria-hidden="true"></i></span>
          <span><?= ucfirst(MISCS); ?></span>
          <input type="radio" name="modelType" value="<?= MISC ?>" class="is-hidden" <?= ($modelType == MISC) ? 'checked' : ''; ?>>
        </a>
        </li>
      </ul>
    </form>
    <!-- End Tabs -->