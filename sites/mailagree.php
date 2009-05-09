<?php

// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();


if (isset($get['user']))
{


$abfrage = "SELECT * FROM ".$dbpräfix."anwaerter WHERE `user`= '".$get['user']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
   $user="$row->user";
   $passwort123="$row->pass";
   $name="$row->name";
   $nachname="$row->nachname";
   $email="$row->email";

   $datum="$row->datum";
   
   // [ADD] 21.04.08 
   $wohnort = "$row->wohnort";
   $geschlecht = "$row->geschlecht";
   $tel = "$row->tel";
   }

if (isset($user) and ($user != ""))
{
$passwort123 = md5($passwort123);   
   
$eintrag = "DELETE FROM `".$dbpräfix."anwaerter` WHERE `user` = '".$get['user']."'";
$eintragen1 = $hp->mysqlquery($eintrag);
echo mysql_error()."<br>";
$eintrag = "INSERT INTO `".$dbpräfix."user`
(user, pass, name, nachname, datum, level, email, wohnort, tel, geschlecht)
VALUES
('$user', '$passwort123', '$name', '$nachname', '$datum', '1', '$email', '$wohnort',  '$tel', '$geschlecht')";
$eintragen2 = $hp->mysqlquery($eintrag);



if (($eintragen1 == true) and ($eintragen2 == true))
{
echo"<br>Vielen Dank,<br>Ihre Registration wurde erfolgreich abgeschlossen!";
echo"<br>Sie sind auserdem, nun im Forum angemeldet. (Die selben Benutzerdaten)";


$hp->PM($superadmin, "System", "Neuer User", "Ein neuer User:<br>$user", $datum, $dbpräfix);


} else { echo "Fehler: <br>Melden Sie sich bitte umgehen bei dem zuständigem Administrator! <br>".mysql_error(); }

} else
{
echo "Ihre E-Mailadresse wurde bereits verifiziert!";
}

}


?>
