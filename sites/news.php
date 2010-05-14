


<!--NEWS-->
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
$lbsites = $hp->lbsites;

// Newschange

if ($right[$level]['newsedit'])
{
$newsidchange=$post['newsid'];

if (isset($post['newsedit'])) {
$newsdatum=$post['newsdate'];
$newstitel=$post['newstitel'];
$newstitel = str_replace('<',"&lt;" ,$newstitel);
$newstyp=$post['newstyp'];
$newstext=$post['newstext'];
$newstext = str_replace('<?',"&lt;?" ,$newstext);
$newstext = str_replace('[bild]',"<img src=\"smilies/" ,$newstext);
$newstext = str_replace('[/bild]',"\">" ,$newstext);
$newslevel=$post['newslevel'];
//$newstext = mysql_real_escape_string($newstext);
$newstitel = mysql_real_escape_string($newstitel);
$newsersteller = mysql_real_escape_string($newsersteller);

$eingabe = "UPDATE `".$dbpräfix."news` SET `datum` = '$newsdatum', `titel` = '$newstitel', `typ` = '$newstyp', `text` = '$newstext', `level`= '$newslevel' WHERE `ID` = '".$newsidchange."';";

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
echo $lang->word('nodelnews')."<br>".$lang->word('questions-webmaster');

} else
{



$eintrag = "DELETE FROM `".$dbpräfix."news` WHERE `ID`= ".$post['newsiddel'];
$eintragen = mysql_query($eintrag);
    if ($eintragen == false)
{
$error->error(mysql_error(), "2");
}
$eintrag2 = "DELETE FROM `".$dbpräfix."kommentar` WHERE `zuid`= ".$post['newsiddel'];
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


// Newswrite
$newskat=$post['newskat'];
$newstitel=$post['newstitel'];
$newstitel = str_replace('<',"&lt;" ,$newstitel);

$newstext=$post['newstext'];

$newstext = str_replace('<?',"&lt;?" ,$newstext);
$newstext = str_replace('[bild]',"<img src=\"smilies/" ,$newstext);
$newstext = str_replace('[/bild]',"\">" ,$newstext);
$newsersteller=$_SESSION['username'];

$newslevel=$post['newslevel'];
$newstyp=$post['newstyp'];
$newsdatum = date('j').".".date('n').".".date('y');
//$newstext = mysql_real_escape_string($newstext);
$newstitel = mysql_real_escape_string($newstitel);
$newsersteller = mysql_real_escape_string($newsersteller);
//$newstext = str_replace("\n",'<br>',$newstext);

//News schreiben!
if (isset($post['newswrite']))
{


if (!$right[$level]['newswrite'])
{
echo $lang->word('nonewswrite')."<br>".$lang->word('questions-webmaster');

} else
{


if (isset($newstitel) and isset($newsersteller) and isset($newsdatum) and isset($newstext) and isset($newslevel))
{

$eintrag = "INSERT INTO `".$dbpräfix."news`
(ersteller, datum, titel, typ, text, level)
VALUES
('$newsersteller', '$newsdatum', '$newstitel', '$newstyp', '$newstext', '$newslevel')";
$eintragen = $hp->mysqlquery($eintrag);


if ($eintragen == true)
{
$goodn=$lang->word('postok');


} else
{$error->error($lang->word('error-post').": ".mysql_error(), "2");}
} else
{
$error->error($lang->word('error-post'),"2");
}
}
$get['delet'] = true;
}
// --------


if (!isset ($limit)) 
{ $limit = 5; }
$limit = $hp->escapestring($limit);
    
   if (!isset ($_SESSION['level'])) {
   $_SESSION['level']=0;
   }




$abfrage = "SELECT * FROM ".$dbpräfix."news ORDER BY `ID` DESC LIMIT ".$limit;
$ergebnis = $hp->mysqlquery($abfrage);
echo mysql_error();

    

 
while($row = mysql_fetch_object($ergebnis))
{
$ok = false;
if (("$row->level" == "1") and ($right[$level]['readl1'] == true ))
{ $ok = true; }
elseif 
(("$row->level" == "2") and ($right[$level]['readl2'] == true ))
{ $ok = true; }
elseif
(("$row->level" == "3") and ($right[$level]['readl3'] == true ))
{ $ok = true; }
elseif
("$row->level" == "0")
{ $ok = true; }

if ($ok == true)
{
 ?>  
 
 <div class="newsbox">
<div class="newsbox_info"><b><?php echo"$row->titel";  if ("$row->level" <> "0") {echo " --Level $row->level --";} ?> <?php echo $lang->word('by')?> <a href=index.php?site=user&show=<?php echo "$row->ersteller"?>><?php echo "$row->ersteller"?></a></b></div>
<div class="newsbox_info_date"><b>  geschrieben am <i><?php echo "$row->datum"?></i></b></div>
<div class="newsbox_inhalt">
<?php echo "$row->text"?>

<br>
               	<?php
                 if (isset($get['delet']))
                 {
if ($right[$level]['newsedit'])
{
//echo '<a href="sites/newschange.php?newsid='.$row->ID.'">News Bearbeiten</a> ';
echo '<a href="index.php?lbsite=newschange&vars='.$row->ID.'" class="lbOn">Bearbeiten</a> ';
}    
if ($right[$level]['newsdel'])
{
echo '<a href="index.php?lbsite=delnews&vars='.$row->ID.'" class="lbOn">Löschen</a> ';
}    
}         
                  ?> 
</div> 
</div>

               	

              
              
              <br>      
<?php }  }   


?>
<p align="center"><font size="3"><a href="index.php?site=news&limit=20"><b><?php echo $lang->word('morenews')?></b></a></font>
<?php
 if ($right[$level]['newswrite'])
{
echo"  -  ".$lbsites->link("newnews","<b>Neue Newsmeldung verfassen</b>"); 
 }
if ($right[$level]['newsedit']) 
{
echo"  -  <a href=\"index.php?site=news&delet=true\"><b>Newsmeldungen Bearbeiten</b></a>"; 
}
 ?></p>
<!--News Ende-->
<?php
ob_end_flush();

?>
