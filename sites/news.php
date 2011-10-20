<?php

// Class Config
$hp = $this;
$right = $hp->getright();
$level = $_SESSION['level'];
$get = $hp->get();
$post = $hp->post();
$dbprefix = $hp->getprefix();
$lang = $hp->getlangclass();
$error = $hp->geterror();
$info = $hp->getinfo();
$lbsites = $hp->lbsites;


$site = new siteTemplate($hp);
$site->load("news");


 if ($right[$level]['newsedit'])
 {


  if (isset($post['newsedit']))
    {
    $newsidchange=$post['newsid'];
    $newsdatum=$post['newsdate'];
    $newstitel=$post['newstitel'];
    $newstitel = str_replace('<',"&lt;" ,$newstitel);
    $newstyp=$post['newstyp'];
    $newstext=$post['newstext'];
    $newstext = str_replace('<?',"&lt;?" ,$newstext);
    $newslevel=$post['newslevel'];
    $newstitel = mysql_real_escape_string($newstitel);
    $eingabe = "UPDATE `".$dbprefix."news` SET `datum` = '$newsdatum', `titel` = '$newstitel', `typ` = '$newstyp', `text` = '$newstext', `level`= '$newslevel' WHERE `ID` = '".$newsidchange."';";

    $ergebnis = mysql_query($eingabe);
    if ($ergebnis == true)
    {
      $info->okm("Newsmeldung erfolgreich geändert!");
    }
    $get['delet'] = true;
    }

 }
// ----


// NewsDel
 if (isset($post['newsdel']))
 {
  if (!$right[$level]['newsdel'])
  {
    $error->error($lang->word('nodelnews'));

  } else
  {
    $eintrag = "DELETE FROM `".$dbprefix."news` WHERE `ID`= ".$post['newsiddel'];
    $eintragen = mysql_query($eintrag);
    if ($eintragen == false)
    {
      $error->error(mysql_error(), "2");
    }
    $eintrag2 = "DELETE FROM `".$dbprefix."kommentar` WHERE `zuid`= ".$post['newsiddel'];
    $eintragen2 = mysql_query($eintrag2);
    if ($eintragen2 == false)
    {
      $error->error(mysql_error(), "2");
    }
  }

  if ($eintragen == true and $eintragen2 == true)
  {
    $info->okn($lang->word('delok'));
  } else
  {
   $error->error($lang->word('error-del')." ".mysql_error(), "2");
  }
  $get['delet'] = true;
 }
// Newsdel

//News schreiben!
 if (isset($post['newswrite']))
 {

  // Newswrite
  $newstitel=$post['newstitel'];
  $newstitel = str_replace('<',"&lt;" ,$newstitel);

  $newstext=$post['newstext'];

  $newstext = str_replace('<?',"&lt;?" ,$newstext);
  $newsersteller=$_SESSION['username'];
  $newslevel=$post['newslevel'];
  $newstyp=$post['newstyp'];
  $newsdatum = date('j').".".date('n').".".date('y');
  $newstitel = mysql_real_escape_string($newstitel);
  $newsersteller = mysql_real_escape_string($newsersteller);

  if (!$right[$level]['newswrite'])
  {
    $error->error($lang->word('nonewswrite')."<br>".$lang->word('questions-webmaster'), "1");
  } else
  {
    if (isset($newstitel) and isset($newsersteller) and isset($newsdatum) and isset($newstext) and isset($newslevel))
    {

     $eintrag = "INSERT INTO `".$dbprefix."news`
     (ersteller, datum, titel, typ, text, level)
     VALUES
     ('$newsersteller', '$newsdatum', '$newstitel', '$newstyp', '$newstext', '$newslevel')";
     $eintragen = $hp->mysqlquery($eintrag);

      if ($eintragen == true)
      {
        $goodn=$lang->word('postok');
      } else
      {
        $error->error($lang->word('error-post').": ".mysql_error(), "2");
      }
    } else
    {
      $error->error($lang->word('error-post'),"2");
    }
  }
 $get['delet'] = true;
}
// --------


 if (!isset ($get['limit']))
 {
  $limit = 5;
 } else
 {
  $limit = intval($get['limit']);
 }
 $limit = $hp->escapestring($limit);

 $abfrage = "SELECT * FROM ".$dbprefix."news ORDER BY `ID` DESC LIMIT ".$limit;
 $ergebnis = $hp->mysqlquery($abfrage);


 $Content = "";

 while($row = mysql_fetch_object($ergebnis))
 {
  $ok = false;
  if (("$row->level" == "1") and ($right[$level]['readl1'] == true ))
    {
     $ok = true;
    } elseif (("$row->level" == "2") and ($right[$level]['readl2'] == true ))
    {
      $ok = true;
    } elseif (("$row->level" == "3") and ($right[$level]['readl3'] == true ))
    {
      $ok = true;
    } elseif ("$row->level" == "0")
    {
      $ok = true;
    }

  if ($ok == true)
  {

    $data = array(
      "titel" => $row->titel,
      "level" => ($row->level <> "0") ? " --Level $row->level --" : "",
      "ersteller" => $row->ersteller,
      "datum" => $row->datum,
      "Content" => $row->text,
      "EditNews" => "",
      "DeletNews" => ""
    );

    if (isset($get['delet']))
    {
     if ($right[$level]['newsedit'])
     {
       $data["EditNews"] = '<a href="index.php?lbsite=newschange&vars='.$row->ID.'" class="lbOn">Bearbeiten</a> ';
     }

     if ($right[$level]['newsdel'])
     {
       $data["DeletNews"] = '<a href="index.php?lbsite=delnews&vars='.$row->ID.'" class="lbOn">Löschen</a> ';
     }
    }

    $Content .= $site->getNode("News", $data);
  }
 }

 $site->set("News", $Content);


 $site->set("WriteNews", "  -  ".$lbsites->link("newnews","<b>Neue Newsmeldung verfassen</b>"));
 $site->set("StartEditNews", "  -  <a href=\"index.php?site=news&delet=true\"><b>Newsmeldungen Bearbeiten</b></a>");

 $site->display();
?>
