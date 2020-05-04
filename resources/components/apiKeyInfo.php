<table style="width: 100%;">
  <tr>
    <th>Permission</th>
    <th>Description</th>
  </tr>
  <?php foreach (KeyHandler::getAllPermissions() as $perm): ?>
    <tr>
      <td><?= $perm[0] ?></td>
      <td><?= $perm[1] ?></td>
    </tr>
  <?php endforeach; ?>
</table>