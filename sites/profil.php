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


  if (!isset($_SESSION['username'])) {
    $error->error($lang->word("error-login-profil") ,"2");
  } else
  {
    if (!isset ($post['pwändern']) and !isset($post['go']) and !isset($post['pwneu']))
    {
      $abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `user` = '".$_SESSION['username']."'";
      $ergebnis = $hp->mysqlquery($abfrage);
    
      
      $row = mysql_fetch_object($ergebnis);
      
      $site = new siteTemplate($hp);
      $site->load("profil");
      
      $data = array(
        "username" => $_SESSION['username'],
        "ID" => $row->ID,
        "name" => $row->name,
        "nachname" => $row->nachname,
        "alter" => $row->alter,
        "geburtstag" => $row->geburtstag,
        "wohnort" => $row->wohnort,
        "tel" => $row->tel,
        "email" => $row->email,
        "cpu" => $row->cpu,
        "ram" => $row->ram,
        "graka" => $row->graka,
        "hdd" => $row->hdd,
        "clan" => $row->clan,
        "clantag" => $row->clantag,
        "clanhomepage" => $row->clanhomepage,
        "clanhistory" => $row->clanhistory
                 
      );
      
      $site->setArray($data);
      $site->display();
      
     
    } elseif (isset ($post['pwändern'])) 
    {
      
      $site = new siteTemplate($hp);
      $site->load("profil_changepw");
      $site->display();
      
    } elseif (isset ($post['pwneu'])) 
    {
      $passwortalt=$post['passwortalt'];
      $passwortneu=$post['passwort'];
      $passwortneu2=$post['passwort2'];
      $passwortalt = md5($passwortalt);

      $site = new siteTemplate($hp);
      $site->load("info");

      if ($passwortalt == $_SESSION['pass'] and $passwortneu == $passwortneu2)
      {
        $passwortneu = md5($passwortneu);
        $eingabe = "UPDATE `".$dbpräfix."user` SET `pass` = '$passwortneu' WHERE `user` = '".$_SESSION['username']."';";
        $ergebnis = $hp->mysqlquery($eingabe);
        $_SESSION['pass']=$passwortneu;
        $site->set("info", $lang->word('ok_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>");
      } else 
      { 
        $site->set("info", $lang->word('pwwrong_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>"); 
      }
      $site->display();
      
    } elseif (isset($post['go'])) 
    {
      $site = new siteTemplate($hp);
      $site->load("info");

      $ignore = array ("hp", "info", "error", "hp", "config", "dbpräfix", "right", "level");
      foreach ($post as $key=>$value) 
      {
        $value = str_replace('<',"&lt;" ,$value);
        $value = str_replace('\'',"\"" ,$value);// , 

        // Leicht Unsicher, da aber alle Variablen, die hier ankommen eh gestrippt werden, ist das irrelevant
        if (!in_array($key, $ignore))
        {
	         $$key = "'".$value."'";
	      }
      }
      
      if ((isset($name)) and (isset($nachname)) and (isset($wohnort))) 
      {

        $eingabe = "UPDATE `".$dbpräfix."user` SET `name` = $name, `nachname` = $nachname, `alter` = $alter, `geburtstag` = $geburtstag, `wohnort` = $wohnort, `cpu` = $cpu,".
        " `ram` = $ram, `graka` = $graka, `hdd` = $hdd, `clan` = $clan, `clantag` = $clantag  WHERE `user` = '".$_SESSION['username']."';";

        $ergebnis2 = $hp->mysqlquery($eingabe);

        $eingabe = "UPDATE `".$dbpräfix."user` SET `clanhomepage` = $clanhomepage, `clanhistory` = $clanhistory, `tel` = $tel  WHERE `user` = '".$_SESSION['username']."';";

        $ergebnis = $hp->mysqlquery($eingabe);
        
        $site->set("info", $lang->word('ok_profil')."!<br><a href=index.php>".$lang->word('back')."</a>");  
      } else
      {
        $site->set("info", $lang->word('notallfields'));
      }
      $site->display();
    }


  }

 ?> 