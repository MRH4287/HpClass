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
$lbs = $hp->lbsites;

// LostPW Seite :)

if (isset($get['change']))
{

$code = $get['change']; 

$sql = "SELECT * FROM `$dbpräfix"."token` WHERE `token` = '$code'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



if ($row->verfall >= time())
{
?>
<p align="center"><strong>Bitte geben Sie ihr Passwort ein:</strong></p>
<p align="center">&nbsp;</p>
<form action="index.php?site=lostpw" method="POST">
<table width="479" height="139" border="0" align="center">
  <tr>
    <td width="129">Benutzername:</td>
    <td width="340"><?php echo $row->user; ?></td>
  </tr>
  <tr>
    <td>Passwort:</td>
    <td><input type="password" name="pw"></td>
  </tr>
  <tr>
    <td>Passwort bestätigung:</td>
    <td><input type="password" name="pw2"></td>
  </tr>
  <tr>
    <td></td>
    <td><input type="hidden" name="token" value="<?php echo $code; ?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><button type="submit" name="change"> <img src="images/ok.gif"> </button></td>
  </tr>
</table>
</form>

<?php

} else
{
echo "Der angegebene Code schon benutzt worden oder ist bereits abgelaufen!";
}

} else if (isset($post['change']))
{

$code = $post['token']; 

$sql = "SELECT * FROM `$dbpräfix"."token` WHERE `token` = '$code'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$pw = $post['pw'];
$pw2 = $post['pw2'];

if ($pw != $pw2)
{
 echo "Die angegebenen Passwörter stimmen nicht überein!";

} else
 {
 
 $sql = "UPDATE `$dbpräfix"."user` SET  `pass` = '".md5($pw)."' WHERE `user` = '$row->user'";
 $erg = $hp->mysqlquery($sql);
 
 $sql = "DELETE FROM `$dbpräfix"."token` WHERE `user` = '$row->user'";
 $erg = $hp->mysqlquery($sql);
 
 echo "Passwort erfolgreich geändert!";
 
 }



} else if (isset($post['lostpw']))
{
$user = $post['username'];
$mail = $post['email'];

$sql = "SELECT * FROM `$dbpräfix"."user` WHERE `user` = '$user'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

if ($row->user == "")
{
echo "Fehler: Benutzername nicht vorhanden!";
$error->error("Benutzername nicht vorhanden!");

echo "<br><a href=?site=lostpw>zurück</a>";
} elseif ($mail != $row->email)
{
echo "Fehler: Die Kontaktemail Adresse ist falsch!";
$error->error("Die Kontaktemail Adresse ist falsch!");

echo "<br><a href=?site=lostpw>zurück</a>";

} else
{
// alles OK
$hp->lostpassword($user);
}

} else
{
?>
<p align="center"><strong>Passwort vergessen:</strong></p>
<p align="center">Bitte Geben Sie zur überprüfung folgende Daten ein:</p>
<p align="center">&nbsp;</p>
<form action="index.php?site=lostpw" method="POST">
<table width="479" height="139" border="0" align="center">
  <tr>
    <td width="129">Benutzername:</td>
    <td width="340"><input type="text" name="username" id="username"></td>
  </tr>
  <tr>
    <td>E-Mail Adresse:</td>
    <td><input type="text" name="email" id="email"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><button type="submit" name="lostpw"> <img src="images/ok.gif"> </button></td>
  </tr>
</table>
</form>
<?php
}
?>
