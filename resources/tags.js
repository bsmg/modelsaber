function tagHTML(text) {
    return '<div class="field has-addons"><p class="control"><input class="input is-rounded" name="tags[]" form="uploadForm" type="text" placeholder="Tag" value="' + text + '"></p><p class="control"><button onclick="this.parentElement.parentElement.parentElement.removeChild(this.parentElement.parentElement)" form="none" class="button is-danger button-delete is-rounded" name="tags[]" form="uploadForm" type="text" placeholder="Tag"></button></p></div>';
    }
const tagContainer = document.getElementById('tag-container');
function addTagInput(text = '') {
    var tags = document.getElementsByName('tags[]');
    var removed = false;
    tags.forEach(function(currentValue, currentIndex, listObj) {
        if (listObj[currentIndex].value == text) {
            removed = true;
            listObj[currentIndex].parentElement.parentElement.parentElement.removeChild(listObj[currentIndex].parentElement.parentElement);
        }
    });
    if (removed != true) {
        tagContainer.insertAdjacentHTML('beforeend', tagHTML(text));
    }
    
}