<?php

// Site Config:
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$config = $hp->getconfig();
$Oright = $hp->right;


$site = new siteTemplate($hp);
$site->load("api");

$site->set("on", ($config["enable_ScriptAccess"]) ? "true" : "false");

$al = array();
$levels = $Oright->getlevels();

foreach ($levels as $k => $level)
{
  if ($Oright->is("allow_ScriptAccess", $level))
  {
    $al[] = $level;
  }
}

$site->set("level", implode(", ", $al));


$user = array();

$sql = "SELECT user, level FROM `$dbprefix"."user`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{

  if (in_array($row->level, $al))
  {
    $user[] = $row->user;
  }

}

$site->set("user", implode(", ", $user));

include "include/api/key.php";

$site->set("code", SHARED_SECRET);



$site->right("superadmin");

$site->display();


?>
