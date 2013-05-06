<?php if(!is_array($module)): ?>
  <p>404. Ingen sådan modul finns.</p>

<?php else: ?>
  <h1>Modul: <?=$module['name']?></h1>

  <h2>Beskrivning</h2>
  <p><?=nl2br($module['doccomment'])?></p>
  <p>Fil: <code><?=$module['filename']?></code></p> 
  
  <h2>Detaljer</h2>
  <table>
    <caption>Detaljer för modulen.</caption>
    <thead><tr><th>Kännetecken</th><th>Gäller för modul</th></tr></thead>

    <tbody>
      <tr><td>Del av Grog Core moduler</td><td><?=$module['isGrogCore']?'Yes':'No'?></td></tr>
      <tr><td>Del av Grog CMF moduler</td><td><?=$module['isGrogCMF']?'Yes':'No'?></td></tr>
      <tr><td>Implementerar interface</td><td><?=empty($module['interface'])?'No':implode(', ', $module['interface'])?></td></tr>
      <tr><td>Controller</td><td><?=$module['isController']?'Yes':'No'?></td></tr>
      <tr><td>Modell</td><td><?=$module['isModel']?'Yes':'No'?></td></tr>
      <tr><td>Använder SQL</td><td><?=$module['hasSQL']?'Yes':'No'?></td></tr>
      <tr><td>Hanterbar som modul</td><td><?=$module['isManageable']?'Yes':'No'?></td></tr>
    </tbody>
  </table>


  <?if(!empty($module['publicMethods'])): ?>
    <h2>Öppna (public) metoder</h2>
    <?php foreach($module['methods'] as $method): ?>
      <?php if($method['isPublic']): ?>
      <h3><?=$method['name']?></h3>
      <p><?=nl2br($method['doccomment'])?></p>
      <p>Implementerade på rad: <?=$method['startline']?> - <?=$method['endline']?>.</p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>


  <?if(!empty($module['protectedMethods'])): ?>
    <h2>Skyddade (protected) metoder</h2>
    <?php foreach($module['methods'] as $method): ?>
      <?php if($method['isProtected']): ?>
      <h3><?=$method['name']?></h3>
      <p><?=nl2br($method['doccomment'])?></p>
      <p>Implementerade på rad: <?=$method['startline']?> - <?=$method['endline']?>.</p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>


  <?if(!empty($module['privateMethods'])): ?>
    <h2>Stängda (private) metoder</h2>
    <?php foreach($module['methods'] as $method): ?>
      <?php if($method['isPrivate']): ?>
      <h3><?=$method['name']?></h3>
      <p><?=nl2br($method['doccomment'])?></p>
      <p>Implementerade på rad: <?=$method['startline']?> - <?=$method['endline']?>.</p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>


  <?if(!empty($module['staticMethods'])): ?>
    <h2>Statiska metoder</h2>
    <?php foreach($module['methods'] as $method): ?>
      <?php if($method['isStatic']): ?>
      <h3><?=$method['name']?></h3>
      <p><?=nl2br($method['doccomment'])?></p>
      <p>Implementerade på rad: <?=$method['startline']?> - <?=$method['endline']?>.</p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>

<?php endif; ?>