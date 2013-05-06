<h1>Exempel på gästbok</h1>
<p>Implementerad i Grog och sparar till databas.</p>

<form action="<?=$form_action?>" method='post'>
  <p>
    <label>Meddelande: <br/>
    <textarea name='newEntry'></textarea></label>
  </p>
  
  <p>
    <input type='submit' name='doAdd' value='Skicka' />
    <input type='submit' name='doClear' value='Rensa ALLA' />
    <input type='submit' name='doCreate' value='Skapa databastabell' />
  </p>
</form>

<h2>Befintliga meddelanden</h2>

<?php foreach($entries as $val):?>

<div style='background-color:#DAB5BA;border:1px solid #000;margin-bottom:1em;padding:1em;width:auto;'>
  <p><?=htmlent($val['entry'])?></p>
  <p style="opacity:0.5">Skickat: <?=$val['created']?></p>
</div>

<?php endforeach;?>
