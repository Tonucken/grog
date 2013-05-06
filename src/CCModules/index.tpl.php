<h1>Modul hantering</h1>

<h2>Om</h2>
<p>
  Modulhanteraren visar information om alla de moduler som ligger i <code>'grog/src/'</code> 
</p>

<h2>Manage Lydia modules</h2>
<p>
  Moduler kan använda <code>IModule</code> som interface för att implementera koden till praktiskt
  användbara delar. Grog innehåller ett interface för att administrera dessa moduler, som till exempel:
  <ul>
    <li><a href='<?=create_url('module/install')?>'>installera</a></li>
  </ul>
</p>

<h2>Aktiva controllers</h2>
<p>
  Det API (application programing interface) som ramverket använder sig av bygger på controllers.
  Aktivera och avaktivera i <code>'grog/site/config.php'</code>
</p>

<ul>
    <?php foreach($controllers as $key => $val): ?>
    <li><a href='<?=create_url($key)?>'><?=$key?></a></li>

        <?php if(!empty($val)): ?>
        <ul>
            <?php foreach($val as $method): ?>
            <li><a href='<?=create_url($key, $method)?>'><?=$method?></a></li>
            <?php endforeach; ?>	
        </ul>
        <?php endif; ?>
    <?php endforeach; ?>	
</ul>