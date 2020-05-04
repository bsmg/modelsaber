<label class="label is-medium">License <?= info(ROOT . '/resources/components/licenseInfo.php', 'small', true); ?></label>
<div class="field is-grouped is-grouped-multiline">
  <div class="control">
    <label class="checkbox">
    <input type="checkbox" name="license[BY]">
  Attribution
</label>
  </div>
  <div class="control">
    <label class="checkbox">
    <input type="checkbox" name="license[SA]">
  ShareAlike
</label>
  </div>
  <div class="control">
    <label class="checkbox">
    <input type="checkbox" name="license[ND]">
  NoDerivatives
</label>
  </div>
  <div class="control">
    <label class="checkbox">
    <input type="checkbox" name="license[NC]">
  NonCommercial
</label>
  </div>
</div>
<div class="field">
  <div class="control">
    <label class="checkbox">
    <input type="checkbox" name="license[NON]" onchange='document.getElementById("licenseLink").disabled = !this.checked;'>
  Non-CC
</label>
  </div>
</div>
<label class="label">Custom License Link</label>
<div class="field flex">
  <div class="control">
    <input type="text" name="license[link]" id="licenseLink" class="input" placeholder="www.linktolicensehere.com" disabled>
  </div>
</div>
<label class="label">Model Source Link</label>
<div class="field flex">
  <div class="control">
    <input type="text" name="license[source]" class="input" placeholder="www.linktosourcehere.com">
  </div>
</div>