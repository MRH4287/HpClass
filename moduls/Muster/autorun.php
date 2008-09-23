<?
// Hier eine Liste aller im Script vorhandenen Variablen:
// $path = Pfad zum Modulverzeichnis (moduls/$path/ für eine korrekte verlinkung!)
// $name = Name des Scripts!
// $run  = Name des PHP Scripts, was ausgeführt wird!
// $template = Templatearray (siehe Beispiel Funktion)
// $hp = HPClass
// $error = Errorclass
// $info = Infoclass
// $lang = Langclass
// $temp = Templatescript (Bitte $template verwenden)



// Funktion zum Einbinden aller Unterseitem im Sitesverzeichnis!
// Achte auf die Beschränkungen!
$handle = @opendir("./moduls/$path/sites"); 
while (false !== ($file = readdir($handle))) {
$n = explode(".", $file);
$a = $n[0];

if (($file != ".") and ($file != ".."))
{
$hp->addredirect("$a", "moduls/$path/sites");
}

}

// Zum Hunzufügen eines Templates benutze folgende Zeile:
// $template['Dein Text']="Changeme";

// Errorausgabe
// $error->error("Name des Errortextes", "Level"); (Level: 1-> Simple Textwiedergabe, 2-> Roter Errorbalken, 3-> Systemkritischer Fehler(System Beenden!))

// $info->info("Ausgabe eines Infotextes");
// $info->okm("Ausgabe einer 'Aufgabe Erfüllt' Nachricht!");


?>
