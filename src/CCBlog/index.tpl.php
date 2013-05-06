<h1>Grog</h1>
<p>Alla bloginlägg av typen <code>'post'</code>. <a href='<?=create_url("content")?>'>läs alla</a></p>

<?php if($contents != null):?>
  <?php foreach($contents as $val):?>
    <h2><?=esc($val['title'])?></h2>
    <p>
      <span class='smaller-text'>
      <em>Skrivet <?=$val['created']?> av <?=$val['owner']?></em>
      </span>

      <?=filter_data($val['data'], $val['filter'])?>

      <span class='smaller-text silent'>
      <a href='<?=create_url("content/edit/{$val['id']}")?>'>redigera</a>
      </span>
    </p>
    <hr>
  <?php endforeach; ?>

  <?php else:?>
  <p>Inga poster ännu.</p>

<?php endif;?>
