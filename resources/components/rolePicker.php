<div id="rolePickerModal" class="modal">
  <script>
  var rolesAr = document.getElementById('roles').value.split(',');
  
  function addOrRemove(array, value) {
    var index = array.indexOf(value);

    if (index === -1) {
        array.push(value);
    } else {
        array.splice(index, 1);
    }
}
  </script>
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head is-klouder">
      <p class="modal-card-title has-text-white">Role Picker&trade;</p>
    </header>
    <section class="modal-card-body">
      <div class="field is-grouped is-grouped-multiline">
      <?php foreach (Role::readAll() as $role): ?>
        <div class="control button <?= (!empty($role->getColor())) ? $role->getColor() : ""; ?> <?= ($user->hasRole($role->getId())) ? 'is-klouder' : ''; ?>" onclick="addOrRemove(rolesAr, '<?= $role->getId(); ?>'); this.classList.toggle('is-klouder')">
          <span><?= $role->getId(); ?></span>
        </div>
      <?php endforeach; ?>
      </div>
      <label class="label">New role</label>
      <div class="field has-addons">
        <div class="control is-expanded">
          <input type="text" class="input" name="addRole" placeholder="type name here">
        </div>
        <div class="control">
          <button type="submit" class="input" name="action" value="createRole" formaction="<?= WEBROOT ?>/forms/adminCreateRole.php">Add role</button>
        </div>
      </div>
      <p class="help" style="color:#fff !important;">This will refresh the page without saving any changes.</p>
    </section>
    <footer class="modal-card-foot">
      <button type="button" class="button is-success" onclick="document.getElementById('roles').value = rolesAr.toString()">Save changes</button>
      <button type="button" class="button" onclick="document.getElementById('rolePickerModal').classList.remove('is-active')">Cancel</button>
    </footer>
  </div>
</div>