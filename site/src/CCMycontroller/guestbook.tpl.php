<h1>Grogbook</h1>

<p>Lämna ett meddelande.</p>

<?=$form->GetHTML()?>

<h2>Senaste meddelanden</h2>

<?php foreach($entries as $val):?>

<div style='background-color:#f6f6f6;border:1px solid #ccc;margin-bottom:1em;padding:1em;'>
  <p>Skapat: <?=$val['created']?></p>
  <p><?=htmlent($val['entry'])?></p>
</div>

<?php endforeach;?>
