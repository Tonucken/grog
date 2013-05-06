<h1>Grog</h1>

<p>Befintliga Grog-inlägg. <span class='smaller-text'><a href='<?=create_url("content/create")?>'>Skriv nytt</a></span></p>

<br><br>

<?php if($contents != null):?>
  <?php foreach($contents as $val):?>
    
  <h3><?=esc($val['title'])?></h3>
    <p> 
      <span class='smaller-text'><em>Skapat: <?=$val['created']?> av <?=$val['owner']?></em></span><br>
      <?=filter_data($val['data'], $val['filter'])?><br>
      <span class='smaller-text silent'><a href='<?=create_url("content/edit/{$val['id']}")?>'>redigera</a></span>
    </p>
  <hr>
    
  <?php endforeach; ?>
  
  <?php else:?>
  <p>Inga poster ännu.</p>
  
<?php endif;?>


