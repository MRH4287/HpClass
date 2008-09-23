<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unbenanntes Dokument</title>
<style type="text/css">
<!--
.Stil1 {
	font-size: 16px;
	font-weight: bold;
}
.Stil3 {font-size: 16px}
.Stil4 {font-size: 24px; }
-->
</style>
</head>

<body>
<div align="center" class="Stil1">
  <p><u>MRH Webseiten Sytem</u></p>
  <p>&nbsp;</p>
  <p align="left"><span class="Stil3">Willkommen beim MRH Webseiten System. Wenn Sie den Umgang mit diesem System einmal Vertanden haben, faellt ihnen die spaetere Bedienung bestimmt nicht schwer!</span></p>
  <p align="left">&nbsp;</p>
  <div align="left" class="Stil4">Installation:</div>
   
  <p align="left"><span class="Stil3">Wenn Sie das System das erste mal nutzen wollen, muessen Sie es zu aller erst Installieren!</span></p>
  <p align="left"><span class="Stil3">D</span>azu muessen Sie den Installations Ordner oeffnen!</p>
  <p align="left">Wenn Ihr Webserver www.mustermann.de heisst und sich ihr System im Dokumenten-Root befindet muessen Sie folgende Adresse oeffnen:</p>
  <p align="left">www.mustermann.de/install</p>
  <p align="left">Dort werden Sie nach Benutzername und Passwort gefragt. Diese Lauten:</p>
  <p align="left">Benutzername: admin</p>
  <p align="left">Passwort: install</p>
  <p align="left">Diese Daten koennen jedoch auch durch aendern der PHP-Datei geaendert werden.</p>
  <p align="left">&nbsp;</p>
  <p align="left" class="Stil4">Templates:</p>
  <p align="left">Das Webseiten System verfuegt ueber die Funktion, mehrere Designs zu laden. </p>
  <p align="left">Dazu muss eine entsprechende HTML in Ihr template Verzeichnis.</p>
  <p align="left">In dieser HTML Datei werden ensprechende HTML Kommentare durch einen PHP Inhalt ersetzt:</p>
  <div align="left"></div>
  <table width="698" border="1">
    <tr>
      <th width="251" scope="col">HTML Kommentar:</th>
      <th width="431" scope="col">Ersetzt durch:</th>
    </tr>
    <tr>
      <th scope="row">&lt;!--next--&gt;</th>
      <td>Inhalt der einzelnen Seiten</td>
    </tr>
    <tr>
      <th scope="row">&lt;!--login--&gt;</th>
      <td>Login Fenster</td>
    </tr>
    <tr>
      <th scope="row">&lt;!--titel--&gt;</th>
      <td>Webseiten Titel der in der config.php definiert wurde</td>
    </tr>
    <tr>
      <th scope="row">&lt;!--headline--&gt;</th>
      <td>Headline - Definiert in der config.php</td>
    </tr>
    <tr>
      <th scope="row">&lt;!--mainheadline--&gt;</th>
      <td>Hauptueberschrifft - Definiert in der config.php</td>
    </tr>
  </table>
  <p align="left">Weitere Template spezifischer Inhalt wird unter templates/&lt;Name der Templates&gt;/template.php bestimmt.</p>
  <p align="left">Das Template wird in der config.php durch das aendern des Eintrages $design geaendert!</p>
  <p align="left">&nbsp;</p>
  <p align="left" class="Stil4">Sprach einstellungen:</p>
  <p align="left">Das Websitensystem ist Multilingual! Was aber nicht heisst, dass bereits jede Unterseite dafuer ausgelegt ist.</p>
  <p align="left">Das Websitensystem ist auf die Deutsche Sprache ausgelegt. </p>
  <p align="left">Beispiele fuer die Verwendung des Sprachsystems finden Sie unter dem Verzeichnis include/lang/</p>
  <p align="left">Die Sprache wird mit dem Parameter lang geaendert.</p>
  <p align="left">Beispiel:</p>
  <p align="left">www.mustermann.de?lang=de -- Deutsche Sprache</p>
  <p align="left">www.mustermann.de?lang=eng -- Englische Sprache (Nicht komplett vorhanden)</p>
  <p align="left">&nbsp;</p>
  <p align="left">Wenn ein Word in dem gewaeltem Sprachpacket nicht gefunden wurde, wird eine Fehlermeldung ausgegeben.</p>
  <p align="left">Zudem werden alle Woerter die davon betroffen sind durch &lt;--!--&gt; Makiert.</p>
  <p align="left">Wenn Sie die Sprache dev Waehlen, Sehen sie alle verwendeten Namen und koennen so ihr Sprachsystem erstellen!</p>
  <p align="left">&nbsp;</p>
  <p align="center" class="Stil4">Modulsystem:</p>
  <p align="left">Seit einem der neusten Updates, gibt es ein Modulsystem!</p>
  <p align="left">Wenn ein Ordner in den moduls Ordner exsistiert, in dem sich eine install.php befindet, wird das System versuchen das entsprechende Modul zu installieren.</p>
  <p align="left">Wenn Sie sich als admin angemeldet haben (Level: 3) und ein Modul zum installieren ist, wird ihnen in der Infozeile eine Meldung angezeigt, dass ein oder mehrer Module zum installieren sind.</p>
  <p align="left">Sie kuennen lernen wie ein Modul geschrieben wird, indem Sie sich die Dateien in moduls/Muster anschauen.</p>
  <p align="left">Die wichtigsten Funktionen eines Moduls:</p>
  <table width="952" border="1">
    <tr>
      <th width="505" scope="row">Funktion</th>
      <td width="437">Beschreibung</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$hp-&gt;addredirect(&quot;!--Name der Seite-!&quot;, &quot;!-Neuer Pfad zur Seite!-&quot;);</div></th>
      <td><p>Laesst das System an einen anderen Pfad zu suchen, wenn eine Unterseite aufgerufen wird.</p>
      <p>Bsp: $hp-&gt;addredirect(&quot;download&quot;, &quot;moduls/download/sites&quot;);</p></td>
    </tr>
    <tr>
      <th scope="row">$info-&gt;info(&quot;...&quot;);</th>
      <td>Gibt eine Infomeldung aus.</td>
    </tr>
    <tr>
      <th scope="row">$info-&gt;okm(&quot;...&quot;);</th>
      <td>Gibt eine &quot;Erfolgreich erledigt Meldung&quot; aus.</td>
    </tr>
    <tr>
      <th scope="row">$error-&gt;error(&quot;...&quot;,&quot;Level&quot;);</th>
      <td><p>Gibt eine Fehlermeldung aus:</p>
      <p>Level:<br />
      1: entspicht echo<br />
      2: Benutzt diese schicke Fehler Zeile<br />
      3: gibt den Fehler aus und fuehrt exit aus. (Stoppt die Webseite)</p>
      </td>
    </tr>
  </table>
  <p align="left"> Hier eine Liste aller im Script vorhandenen Variablen:<br />
  </p>
  <table width="617" border="1">
    <tr>
      <th width="102" scope="row"><div align="left">Variable</div></th>
      <td width="505">Beschreibung</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$path</div></th>
      <td>Pfad zum Modulverzeichnis (moduls/$path/ fuer eine korrekte verlinkung!)</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$name</div></th>
      <td>Name des Scripts!</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$run</div></th>
      <td>Name des PHP Scripts, was ausgefuehrt wird!</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$template<br />
      </div></th>
      <td>Templatearray (siehe Beispiel Funktion)</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$hp</div></th>
      <td>HPClass</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$error</div></th>
      <td>Errorclass</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$info</div></th>
      <td> Infoclass</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$lang</div></th>
      <td>Langclass</td>
    </tr>
    <tr>
      <th scope="row"><div align="left">$temp</div></th>
      <td>Templatescript (Bitte $template verwenden)</td>
    </tr>
  </table>
  <p align="left">&nbsp;</p>
  <p align="left">&nbsp;</p>
  <p align="left">Dieses Skript ist allein fuer den Privaten Gebrauch gedacht!</p>
  <p align="left">&nbsp;</p>
  <p align="left">Alle Umlaute wegen Darstellungsfehlern entfernt.</p>
</div>
</body>
</html>
