<div id="tagPickerModal" class="modal">
  <script>
    var tagsAr = [];
    window.onload = function() {
      document.getElementsByName('tags[]').forEach(function(val, index, list) {
        addTag(list[index].value, false);
      });
    };
  
  
  function addOrRemove(array, value) {
    var index = array.indexOf(value);
    if (index === -1) {
        array.push(value);
    } else {
        array.splice(index, 1);
    }
  }
  
  function addTag(name, addToList = true) {
    if (!removeEmpty(name)) {
      return;
    }
    var tags = document.getElementById('tagPickerContainer');
    
    var tag = document.createElement('div');
    tag.classList.add('control');
    tag.classList.add('button');
    tag.classList.add('is-klouder');
    tag.setAttribute('onclick', "addTagInput('" + name + "'); this.classList.toggle('is-klouder')");
    
    var span = document.createElement('span');
    var text = document.createTextNode(name);
    
    span.appendChild(text);
    tag.appendChild(span);
    tags.appendChild(tag);
    
    if (addToList) {
      addTagInput(name);
    }
    
    addOrRemove(tagsAr, name);
  }
  
  function saveTags() {
    var output = tagsAr.filter(removeEmpty);
    output.forEach(addTagToInput);
  }
  
  function addTagToInput(tag) {
    addTagInput(tag);
  }
  
  function removeEmpty(el) {
      return el != null && el != "";
  }
  </script>
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head is-klouder">
      <p class="modal-card-title has-text-white">Tag Picker&trade;</p>
    </header>
    <section class="modal-card-body">
      <div id="tagPickerContainer" class="field is-grouped is-grouped-multiline">
      <?php foreach ($helper->getQuickTags() as $tag): ?>
        <div class="control button <?= (isset($model) && $model->hasTag($tag)) ? 'is-klouder' : '' ?>" onclick="addTagInput('<?= str_replace(["\r", "\n"], '', $tag) ?>'); this.classList.toggle('is-klouder')">
          <span><?= $tag ?></span>
        </div>
      <?php endforeach; ?>
      </div>
      <label class="label">New tag</label>
      <div class="field has-addons">
        <div class="control is-expanded">
          <input type="text" id="newTagName" class="input" placeholder="type tag here">
        </div>
        <div class="control">
          <button type="button" class="input" onclick="addTag(document.getElementById('newTagName').value); document.getElementById('newTagName').value = ''">Add tag</button>
        </div>
      </div>
      <p class="help" style="color:#fff !important;">This will refresh the page without saving any changes.</p>
    </section>
    <footer class="modal-card-foot">
      <button type="button" class="button" onclick="document.getElementById('tagPickerModal').classList.remove('is-active')">Done</button>
    </footer>
  </div>
</div>