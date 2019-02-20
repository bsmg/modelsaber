const tagHTML = '<div class="field has-addons"><p class="control"><input class="input is-rounded" name="tags[]" form="uploadForm" type="text" placeholder="Tag"></p><p class="control"><button onclick="this.parentElement.parentElement.parentElement.removeChild(this.parentElement.parentElement)" form="none" class="button is-danger button-delete is-rounded" name="tags[]" form="uploadForm" type="text" placeholder="Tag"></button></p></div>'
const tagContainer = document.getElementById('tag-container')
function addTagInput() {
  tagContainer.insertAdjacentHTML('beforeend', tagHTML)
}