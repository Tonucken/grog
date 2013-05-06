<?php if($content['created']): ?>
  <h1>Redigera innehåll</h1>
  <p>Du kan redigera och spara detta.</p>
  <?php else: ?>

  <h1>Skapa innehåll</h1>
  <p>Skriv något nytt att lägga till.</p>
<?php endif; ?>

<?=$form->GetHTML(array('class'=>'content-edit'))?>

<p class='smaller-text'>
<em>
  <?php if($content['created']): ?>
  Skrivet av <?=$content['owner']?> för <?=time_diff($content['created'])?> sedan.
  <?php else: ?>
  
  Ej tillagt än.
  <?php endif; ?>

  <?php if(isset($content['updated'])):?>
  Senast uppdaterat för <?=time_diff($content['updated'])?> sedan.
  <?php endif; ?>
</em>
</p>

<p>
  <a href='<?=create_url('content', 'create')?>'>Skriv nytt</a>
  <a href='<?=create_url('page', 'view', $content['id'])?>'>Läs</a>
  <a href='<?=create_url('content')?>'>Läs alla</a>
</p>


