function copyToClipboard(element) {
  /* Select the text field */
  element.select();
  element.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");
}

function toggleDisabled(id, value) {
  element = document.getElementById(id);

  if (value) {
    element.classList.add('disable-link')
  } else {
    element.classList.remove('disable-link')
  }
}

function isBoolean(value) {
  return (typeof value === 'boolean');
}
function isNumber(value) {
  return (typeof value === 'number');
}
function isString(value) {
  return (typeof value === 'string' || value instanceof String);
}
function isEmpty(value) {
  return (value === '');
}