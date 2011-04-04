<?php
session_start();
include("../include/config.php");
//MySQL Connect

$db = mysql_connect($dbserver,
$dbuser,$dbpass)
or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";
 
mysql_select_db($dbdatenbank, $db)
or print "Die Datenbank existiert nicht.";


$user=$_POST['user'];
$passwort=$_POST['passwort'];

$user = mysql_real_escape_string($user); 

$passwort = md5("pw_".$passwort);

$ok = false;
if (isset($_POST['login'])) 
{

$abfrage = "SELECT * FROM ".$dbpräfix."user";
$ergebnis = mysql_query($abfrage);
    
    
while($row = mysql_fetch_object($ergebnis))
   {
    if ($user == "$row->user" and $passwort == "$row->pass")
    {
       $_SESSION['username']="$user";
       $ok=true; 
    } elseif ($user == "$row->user" and $passwort != $row->pass) // Kein Md5
    {
      if (($passwort == md5($row->pass)) or  (md5($_POST['passwort']) == $row->pass))
      {
      // KEin MD5 ...
      $sql = "UPDATE `".$dbpräfix."user` SET `pass` = '".md5("pw_".$_POST['passwort'])."' WHERE `user` = '".$user."'";
      mysql_query($sql);
      echo mysql_error();
      $_SESSION['username']="$user";
      $ok = true;
      }
   
    }
   }
 if ($ok)
 {

 $abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `user` = '".$user."'";
 $ergebnis =  mysql_query($abfrage);
 while($row = mysql_fetch_object($ergebnis))
   {
   $_SESSION['level']="$row->level";
   $_SESSION['pass']="$row->pass";
   $_SESSION['ID'] = $row->ID;
   }
  $time =(int) time();
  $eingabe2 = "UPDATE `".$dbpräfix."user` SET `lastlogin` = '$time' WHERE `user` = '".$user."';";
  $ergebnis2 = mysql_query($eingabe2);
  echo mysql_error();
 }
}
if (isset($_GET['logout']))
{
  $user = $_SESSION['username'];
  session_unregister("username");
  session_unregister("level");
  setcookie ("username", "", time() -1);
  setcookie ("level", "", time() -1);
  session_destroy();
  $time =(int) time();
}
if ($ok == true)
{
  if (isset($_GET['logout']))
  {
    $loginok="lo";
  }else{
    $loginok="j";
  }
} else
{
  $loginok="n";
}
if (!isset($_POST['login']))
{
  $loginok="j";
}

header ("Location: ../index.php?login=$loginok");

?>
