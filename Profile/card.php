<?php
require_once '../resources/includes/constants.php';
header("Content-type: image/svg+xml");
require_once '../user.php';

  $profileUser = new User();
  $profileUser->read($_GET['user']);
  
  $nameImage = $profileUser->getIcon();
  include_once 'ProfileCardWeb.svg';
  $username = ($profileUser->isBobby()) ? $profileUser->bobbyfy($profileUser->getUsername()) : $profileUser->getUsername();
  $discord = $profileUser->getUsername() . '#' . $profileUser->getDiscriminator();
  $description = ($profileUser->isBobby()) ? $profileUser->bobbyfy($profileUser->getDescription()) : $profileUser->getDescription();
?>
<script>
  var svgTextElement = document.getElementById("description");
//  svgTextElement.textContent = "";
  function printText(text) {
    var data = document.createElementNS("http://www.w3.org/2000/svg", "tspan");
//    data.nodeValue = text;
    data.textContent = text;
    data.setAttribute('dy', '1.2em');
    data.setAttribute('x', '1em');
    svgTextElement.appendChild(data);
  }
  
var image = document.getElementById('avatarBig');
image.setAttribute('href', '<?= $profileUser->getAvatar(); ?>?size=1024');
var imageSmall = document.getElementById('avatarSmall');
imageSmall.setAttribute('href', '<?= $profileUser->getAvatar(); ?>?size=128');

var layer2 = document.getElementById("layer2");

var username = document.getElementById("username");
username.textContent = "<?= $username; ?>";

var icon = document.createElementNS("http://www.w3.org/2000/svg", "image");
//var icon = document.createElement("image");
icon.setAttribute('width', '6px');
icon.setAttribute('height', '6px');
icon.setAttribute('x', '2');
icon.setAttribute('y', '-8');
icon.setAttribute('display', 'inline-block');
icon.setAttribute('href', "<?= $nameImage['image']; ?>");
//icon.setAttribute('title', "<?= $nameImage['title']; ?>");
//username.appendChild(icon);
//layer2.insertAdjacentElement('afterend', icon);
layer2.insertBefore(icon, username);

var discord = document.getElementById("discord");
discord.textContent = "<?= $discord; ?>";

var chunks = [];
var str = "<?= $description; ?>";
//for (var i = 0, charsLength = str.length; i < charsLength; i += 35) {
//    chunks.push(str.substring(i, i + 35));
//}
//chunks.forEach(printText);
var textNode = svgTextElement.childNodes[0];
//textNode.nodeValue = "<?= $description; ?>";
svgTextElement.textContent = "<?= $parsedown->line($description); ?>";

</script>