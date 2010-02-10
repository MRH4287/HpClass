<?php
class login
{
var $login;
var $config;

function __construct()
{
// Standard Config:
$this->config = array(

"title_front" => "<font color=\"black\">",
"title_back" => "</font>",
"list_front" => "<font color=\"black\">",
"list_back" => "</font>",
"list_symbol" => "&raquo;&nbsp;",
"list_behind_link" => "",
"list_before_link" => ""

);


}

function tf()
{
return $this->config["title_front"];
}

function tb()
{
return $this->config["title_back"];
}

function lf()
{
return $this->config["list_front"];
}
function lb()
{
return $this->config["list_back"];
}
function ls()
{
return $this->config["list_symbol"];
}

function lvl()
{
return $this->config["list_behind_link"];
}

function lnl()
{
return $this->config["list_before_link"];
}


function addstr($str)
{
$this->login = $this->login.$str;
}

function getlogin()
{
return $this->login;
}

function getconfig($template, $design)
{
 if (is_object($template))
 {
  $config = $template->getloginconfig($design);
  
   if (is_array($config))
   {
    $this->config = $config;
   }


 }
}


}

$login = new login;
$login->getconfig($temp, $design);
// Short Cut
$l = $login;

$dbpräfix = $hp->getpräfix();

// Hier kommt der Logintext
//$login->addstr('<p align="left">');

 if (!isset($_SESSION['username'])) { 
 
      $login->addstr('<form method="POST" action="sites/login.php">
      '.$l->tf().$lang->word('username').':'.$l->tb().'<br>
      <input type="text" name="user" size="15"><br>
      '.$l->tf().$lang->word('password').':'.$l->tb().'<br>
      <input type="password" name="passwort" size="15"><input type="submit" value="'.$lang->word('loginbutt').'" name="login">
      '.$l->lvl().'<a href=index.php?site=register>'.$l->lf().$l->ls().$lang->word('register').$l->lb().'</a>'.$l->lnl().'
      '.$l->lvl().'<a href=index.php?site=lostpw>'.$l->lf().$l->ls().'Passwort vergesen?'.$l->lb().'</a>'.$l->lnl().'
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
 
    
   $login->addstr($l->tf().$lang->word('loggedas').$l->tb().'<br><br>
   
   <b>'.$l->tf().$_SESSION['username']." (".$_SESSION['level'].')'.$l->lb().'</b><br>
   
   
    '.$l->lvl().'<a href=sites/login.php?logout>'.$l->lf().$l->ls()."Logout".$l->lb().'</a>'.$l->lnl().'
    
    '.$l->lvl().'<a href=index.php?site=admin>'); if ($number != 0) { $login->addstr("<b>"); } $login->addstr($l->lf().$l->ls()."Administration".$l->lb()); if ($number != 0) { $login->addstr("</b>"); } $login->addstr('</a>'.$l->lnl().'
    '.$l->lvl().'<a href=index.php?site=pm>'); if ($number2 != 0) { $login->addstr("<b>"); } $login->addstr($l->lf().$l->ls()."PM-Menu".$l->lb()); if ($number2 != 0) {  $login->addstr("</b>"); } $login->addstr('</a>'.$l->lnl().'
    '.$l->lvl().'<a href=index.php?site=profil>'.$l->lf().$l->ls().$lang->word('editprofile').$l->lb().'</a>'.$l->lnl());
     
    if (in_array($_SESSION['username'], $hp->getsuperadmin()))
    {
    
        $login->addstr($l->lvl().'<a href=index.php?site=rights>'.$l->lf().$l->ls()."Rechte".$l->lb().'</a>'.$l->lnl());
        $login->addstr($l->lvl().'<a href=index.php?site=config>'.$l->lf().$l->ls()."Konfiguration".$l->lb().'</a>'.$l->lnl());
        $login->addstr($l->lvl().'<a href=index.php?site=dragdrop>'.$l->lf().$l->ls()."Widget System".$l->lb().'</a>'.$l->lnl());
    
    }
}
//$login->addstr('</p>');

// Übergabe des Wertes
$template['login'] = $login->getlogin();
?>
