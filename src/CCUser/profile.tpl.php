<h1>Användarprofil</h1>
<p>Läs och redigera din profil.</p>

<?php if($is_authenticated): ?>
  <?=$profile_form?>
  <p>Profil skapad <?=$user['created']?> och uppdaterades senast <?=$user['updated']?>.</p>

  <p>Du är med i följande grupper: <?=count($user['groups'])?></p>
    <ul>
      <?php foreach($user['groups'] as $group): ?>
      <li><?=$group['name']?>
      <?php endforeach; ?>
    </ul>

    <?php else: ?>
    <p>Användaren är anonym och inte autentiserad.</p>
<?php endif; ?>
