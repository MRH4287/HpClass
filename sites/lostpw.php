<?php
// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbpr�fix = $hp->getpr�fix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$lbs = $hp->lbsites;

// LostPW Seite :)

if (isset($get['change']))
{

$code = $get['change']; 

$sql = "SELECT * FROM `$dbpr�fix"."token` WHERE `token` = '$code'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);



if (isset($row->verfall) and ($row->verfall >= time()))
{

 $data = array(
  "user" => $row->user,
  "code" => $code
 );
 
 $site = new siteTemplate($hp);
 $site->load("lostpw");
 $site->setArray($data);
 $site->display("Change");


} else
{
 $error->error("Der angegebene Code schon benutzt worden oder ist bereits abgelaufen!", "1");
}

} else if (isset($post['change']))
{

$code = $post['token']; 

$sql = "SELECT * FROM `$dbpr�fix"."token` WHERE `token` = '$code'";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

$pw = $post['pw'];
$pw2 = $post['pw2'];

if ($pw != $pw2)
{
 $error->error("Die angegebenen Passw�rter stimmen nicht �berein!", "1");

} else
 {
 
 $sql = "UPDATE `$dbpr�fix"."user` SET  `pass` = '".md5($pw)."' WHERE `user` = '$row->user'";
 $erg = $hp->mysqlquery($sql);
 
 $sql = "DELETE FROM `$dbpr�fix"."token` WHERE `user` = '$row->user'";
 $erg = $hp->mysqlquery($sql);
 
 $site = new siteTemplate($hp);
 $site->load("info");
 $site->set("info", "Passwort erfolgreich ge�ndert!");
 $site->display();
 
 }



} else if (isset($post['lostpw']))
{
  $user = $post['username'];
  $mail = $post['email'];

  $sql = "SELECT * FROM `$dbpr�fix"."user` WHERE `user` = '$user'";
  $erg = $hp->mysqlquery($sql);
  $row = mysql_fetch_object($erg);

  if ($row->user == "")
  {
    $site = new siteTemplate($hp);
    $site->load("info");

    $site->set("info", "Fehler: Benutzername nicht vorhanden!<br><a href=?site=lostpw>zur�ck</a>");
    $error->error("Benutzername nicht vorhanden!");
    $site->display();
    
  } elseif ($mail != $row->email)
  {
    $site = new siteTemplate($hp);
    $site->load("info");
    
    $site->set("info", "Fehler: Die Kontaktemail Adresse ist falsch!<br><a href=?site=lostpw>zur�ck</a>");
    $error->error("Die Kontaktemail Adresse ist falsch!");
    $site->display();

  } else
  {
  // alles OK
  $hp->lostpassword($user);
  }

} else
{
    $site = new siteTemplate($hp);
    $site->load("lostpw");
    $site->display();

}
?>
