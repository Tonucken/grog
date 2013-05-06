<h1>Innehåll</h1>
<p>
Skriv, redigera och läs innehåll. <a href='<?=create_url('content/create')?>'>Skriv nytt</a> <a href='<?=create_url('blog')?>'>Visa som blog</a>
</p>

<h3>Allt innehåll</h3>
<?php if($contents != null):?>

  <ul>
  <?php foreach($contents as $val):?>
    <li><?=$val['id']?> - <?=esc($val['title'])?> av <?=$val['owner']?> 
    <a href='<?=create_url("content/edit/{$val['id']}")?>'>redigera</a> 
    <a href='<?=create_url("page/view/{$val['id']}")?>'>läs</a>
  <?php endforeach; ?>
  </ul>
  
  <?php else:?>
  <p>Inget innehåll än.</p>
<?php endif;?>

