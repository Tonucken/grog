<h1>Teman</h1>

<p>
  Detta är bara en hjälpsida för att användas vid utveckling och testande.<br>
  Aktuellt tema som används nu: <?=$theme_name?><br><br>
  
  Lista över relevanta metoder:
  <ul>
    <?php foreach($methods as $val): ?>
    <li><a href='<?=create_url($val)?>'><?=$val?></a>
    <?php endforeach; ?>
  </ul>
</p>
