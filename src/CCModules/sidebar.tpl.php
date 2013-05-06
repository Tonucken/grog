<div class='box'>
  <h4>Alla moduler</h4>
  <p>Alla Grog-moduler.</p>
  <ul>
    <?php foreach($modules as $module): ?>
    <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>GrogCore</h4>
  <p>Alla moduler i GrogCore.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isGrogCore']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Grog CMF</h4>
  <p>Grog Content Management Framework moduler.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isGrogCMF']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Modeller</h4>
  <p>En klass räknas som modul om filnamnet startar med CM (ClassModule)</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isModel']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Controllers</h4>
  <p>Implementerar interface <code>IController</code>.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isController']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Hanterbar modul</h4>
  <p>Implementerar interface <code>IModule</code>.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['isManageable']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Innehåller SQL</h4>
  <p>Implementerar interface <code>IHasSQL</code>.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if($module['hasSQL']): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>


<div class='box'>
  <h4>Övriga moduler</h4>
  <p>Moduler som inte implementerar något Grog-specifikt interface för sin index-action.</p>
  <ul>
    <?php foreach($modules as $module): ?>
      <?php if(!($module['isController'] || $module['isGrogCore'] || $module['isGrogCMF'])): ?>
      <li><a href='<?=create_url("module/view/{$module['name']}")?>'><?=$module['name']?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>