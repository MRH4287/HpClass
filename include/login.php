<?php
class login
{
var $login;

function addstr($str)
{
$this->login = $this->login.$str;
}

function getlogin()
{
return $this->login;
}

}

$login = new login;

$dbpräfix = $hp->getpräfix();

// Hier kommt der Logintext
//$login->addstr('<p align="left">');

 if (!isset($_SESSION['username'])) { 
 
      $login->addstr('<form method="POST" action="sites/login.php">
      <font color="black">'.$lang->word('username').':</font><br>
      <input type="text" name="user" size="15"><br>
      <font color="black">'.$lang->word('password').':</font><br>
      <input type="password" name="passwort" size="15"><input type="submit" value="'.$lang->word('loginbutt').'" name="login">
      <a href=index.php?site=register><font color="black">&raquo;&nbsp;'.$lang->word('register').'</font></a>
    </form>');
     } else { 
     $abfrage = "SELECT * FROM ".$dbpräfix."anwaerter";
$ergebnis = $hp->mysqlquery($abfrage);
    if ($ergebnis == false)
{
echo mysql_error();
}
    $number=0;
while($row = mysql_fetch_object($ergebnis))
   { $number=$number+1; }

$abfrage = "SELECT * FROM ".$dbpräfix."pm  WHERE `zu` = '".$_SESSION['username']."' ORDER BY `ID`;";
$ergebnis = $hp->mysqlquery($abfrage);
    if ($ergebnis == false)
{
echo mysql_error();
}
    while($row = mysql_fetch_object($ergebnis))
   {  if ("$row->gelesen" == "0")
   {
   $number2=$number2+1;
   }
    }
    //    $br = "<br>"; 
    
   $login->addstr('<font color="black">'.$lang->word('loggedas').'</font><br><br>
   
   <b><font color="black"> '.$_SESSION['username']." (".$_SESSION['level'].') </font></b><br>
   
   
    
    <a href=sites/login.php?logout><font color="black">&raquo;&nbsp;Logout</font></a>'.$br.'
    

    <a href=index.php?site=admin>'); if ($number != 0) { $login->addstr("<b>"); } $login->addstr('<font color="black">&raquo;&nbsp;Administration</font>'); if ($number != 0) { $login->addstr("</b>"); } $login->addstr('</a>'.$br.'
    <a href=index.php?site=pm>'); if ($number2 != 0) { $login->addstr("<b>"); } $login->addstr('<font color="black">&raquo;&nbsp;PM-Menu</font>'); if ($number2 != 0) {  $login->addstr("</b>"); } $login->addstr('</a>'.$br.'
    <a href=index.php?site=profil><font color="black">&raquo;&nbsp;'.$lang->word('editprofile').'</font></a>'.$br.'');
     
    if (($_SESSION['username'] == "admin") or ($_SESSION['username'] == "mrh"))
    {
    
        $login->addstr('<a href=index.php?site=rights><font color="black">&raquo;&nbsp;Rechte</font></a>'.$br.'');
        $login->addstr('<a href=index.php?site=config><font color="black">&raquo;&nbsp;Konfiguration</font></a>'.$br.'');
    
    }
}
//$login->addstr('</p>');

// Übergabe des Wertes
$template['login'] = $login->getlogin();
?>
