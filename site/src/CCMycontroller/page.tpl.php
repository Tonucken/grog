<?php if($content['id']):?>
  
  <h1><?=esc($content['title'])?></h1>

  <p>
    <?=$content->GetFilteredData()?>
    <br><span class='smaller-text silent'><a href='<?=create_url("content/edit/{$content['id']}")?>'>redigera</a> 
    <a href='<?=create_url("content")?>'>läs alla</a></span>
  </p>

<?php else:?>
  <p>404: Sidan finns inte.</p>
<?php endif;?>
