Välkommen till ramverket GROG, byggt av Gringo som projektuppgift i distanskursen phpmvc vid Blekinge Tekniska Högskola, vårterminen 2013.
Det är byggt på följande resurser:
Lydia - https://github.com/mosbth/lydia
HTML-purifier - http://htmlpurifier.org/
Lessphp - http://leafo.net/lessphp/
Semantic grid system - http://semantic.gs/

Se min installation på - http://www.student.bth.se/~toha12/phpmvc/grog/



All källkod finns att granska och hämta på https://github.com/Tonucken/grog 

INSTALLERA: 

1. Öppna Git Bash på önskad adress och kör 'git clone git://github.com/Tonucken/grog.git'

2. I filen '.htaccess' som ligger i ditt root-directory (samma som denna filen) ändrar du adressen för rewrite-base till
din adress, om din webhost kräver detta. Eventuellt behöver du visa dolda filer i mappen för att kunna se '.htaccess'

3. Mappen 'site/data' måste vara skrivbar med "chmod 777" eftersom det är där databasfilen för gästbok och blog ska vara.
Steg 4, 5, 6 är valfria, och du kan gå direkt till steg 7 om du vill.

4. Root-mapparna 'src' och 'themes' ska du normalt sett inte behöva göra några ändringar i, utan allt som rör just din
sida ska kunna utföras i rootmappen 'site' och dess undermappar. Undantaget är om du vill hantera stylesheet på bara ett
enda ställe. Det rekommenderas att du gör dina egna ändringar i 'site/themes/userstheme/style.css'. Om du tänker göra det 
kan du hoppa över nästa punkt.

5. Tillägg och ändringar i din lokala stylesheet prioriterar över eventuella konflikter mot det som är definierat i 
'themes/grogstyle/style.less' så det räcker normalt sett. Om du ändå vill hålla allt med stylesheet på ett enda ställe
är det några saker du behöver känna till. Grogs stylesheet anropas i 'site/config.php' mot filen 'style.php' snarare 
än en '.css' fil. Detta eftersom det är byggt i 'style.less' som sedan kompileras till en läsbar stylesheet och även 
en '.cache' av densamma om ändringar gjorts. Du bör alltså vara väl insatt i såväl vanlig css-kod, samt lessphp och 
semantic.gs för att sköta ändringar här. Om du nu väljer att göra det så behöver du göra mappen 'grogstyle' skrivbar 
med "chmod 777" för att '.cache'-filen ska kunna skrivas.

6. I filen 'site/config.php' finns inställningar för hur felmeddelanden ska hanteras. Default-inställningen är att de skrivs
ut, men det ger ett proffsigare intryck för besökare om de inte behöver ta del av alla eventuella fel. Därför finns det några
rader under som i nuläget är utkommenterade med /* snedstreck och stjärna på det här viset */. Om du tar bort de tecknen
från den biten kod och istället sätter dem runt det som är nuvarande settings så sparas felmeddelanden till en loggfil
istället för att skrivas ut på sidan. Om du väljer den varianten behöver även mappen 'site/felmeddelanden' göras skrivbar
med "chmod 777".

7. Klicka på 'modul/installera' som du hittar längst ner på Grogs framsida.

8. Som default skapas en administratör som loggar in med anv/lösen root:root och en vanlig användare doe:doe

9. Klicka på  'Min Grog' i navigeringsmenyn, och längst upp finns en länk för att skriva nytt innehåll. Under 'Typ' anger du 'post' 
för blog-inlägg eller 'page' för nytt innehåll på egna sidor. För att implementera en ny sida går du in på 'site/src/CCMyontroller' 
och copypastear koden under /** Me-sidan */ och ändrar siffran 4 till den id-siffra din skapade sida tilldelats i databasen. Ändra 
titeln på raden under till vad du vill sidan ska heta. Sedan går du till config.php och lägger in sidans uppgifter i arrayen 
för my-navbar.
