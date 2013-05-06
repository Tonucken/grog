<h1>Index Controller</h1>
<p>Välkommen till Ramverket Grog. Detta är din startsida som administratör för lite inledande överblick
av vad du har för tillgängliga verktyg aktiverade. I 'grog/site/config.php::controllers' kan du själv ändra 
vilka verktyg du vill ha aktiva eller inte genom att ändra true/false.
</p>

<h2>Download</h2>
<p>
  Ladda ner ramverket från mitt konto på GitHub genom att antingen klona hela paketet direkt med:  
    <blockquote>
    <code>git clone git://github.com/Tonucken/grog.git</code>
    </blockquote>
  Eller kolla igenom filerna manuellt på <a href='https://github.com/Tonucken/grog'>https://github.com/Tonucken/grog</a>
</p>

<h2>Installera</h2>
<p>
  Ramverket använder SQLite för att lagra meddelanden internt i databaser på sidan. Det innebär att du inte
  behöver konfigurera kopplingar till externa konton eller användare. Däremot behöver mappen 'grog/site/data/'
  vara skrivbar för att kunna skapa och ändra filer. Det kan göras på lite olika sätt, antingen via din ftp-klient 
  om du använder en sådan, eller om du använder linux genom:
    <blockquote>
    <code>cd grog; chmod 777 site/data</code>
    </blockquote>
  Valfritt: Om du inte vill att felmeddelanden ska skrivas ut synligt för besökare finns det en kodsnippet i 'grog/site/config.php'
  som gör att de istället sparas i en logfil. Om du föredrar det alternativet i drift är det bara att ta bort kommentars-stjärnorna
  runt den kodbiten och sätta runt nuvarande error-settings istället. I så fall behöver även 'grog/site/errorlog/' göras skrivbar 
  på samma sätt.
</p>

<h2>Initiera</h2>
<p>
  Några moduler behöver initiera sina databaser innan de kan börja användas. Det behöver bara göras en gång och kan
  utföras manuellt om du vill, eller för enkelhets skull bara klicka här:
    <blockquote>
    <a href='<?=create_url('module/install')?>'>modul/installera</a>
    </blockquote>
</p>