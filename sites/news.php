
<div class="headline">
  <p align="center"><font face="Haettenschweiler" size="5">News:</font></div>

<!--NEWS-->
<?

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
$lbsites = $hp->lbsites;

// Newschange
?>
<script src="./js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
<?
$newsidchange=$post['newsid'];

if ($_POST ['newswrite'] == "�ndern") {
$newsdatum=$_POST['newsdate'];
$newstitel=$_POST['newstitel'];
$newstitel = str_replace('<',"&lt;" ,$newstitel);
$newstyp=$_POST['newstyp'];
$newstext=$_POST['newstext'];
$newstext = str_replace('<?',"&lt;?" ,$newstext);
$newstext = str_replace('[bild]',"<img src=\"smilies/" ,$newstext);
$newstext = str_replace('[/bild]',"\">" ,$newstext);
$newslevel=$_POST['newslevel'];
//$newstext = mysql_real_escape_string($newstext);
$newstitel = mysql_real_escape_string($newstitel);
$newsersteller = mysql_real_escape_string($newsersteller);

$eingabe = "UPDATE `".$dbpr�fix."news` SET `datum` = '$newsdatum', `titel` = '$newstitel', `typ` = '$newstyp', `text` = '$newstext', `level`= '$newslevel' WHERE `ID` = '".$newsidchange."';";

$ergebnis = mysql_query($eingabe);
  if ($ergebnis == true)
  {
  $info->okm("Newsmeldung erfolgreich ge�ndert!");
  } 
$get['delet'] = true;
 }
// ----


if (!isset ($limit)) 
{ $limit = 5; }
$limit = $hp->escapestring($limit);
    
   if (!isset ($_SESSION['level'])) {
   $_SESSION['level']=0;
   }




$abfrage = "SELECT * FROM ".$dbpr�fix."news ORDER BY `ID` DESC LIMIT ".$limit;
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
      <table class="liste" width="100%" border="1" bordercolor="#000000" bordercolorlight="#000000">
      <tr>
      
            <th bgcolor="<?=$defaultcolor?>">

               <table width="100%" cellpadding="0" height="48">
               <tr>
               	<td rowspan="2" height="46"><img src="images/news_<?="$row->typ"?>.gif" width="40" height="40"></td>
               	<td colspan="2" style="padding-bottom: 1px;" height="22" valign="bottom" width="100%"><span><b><? echo"$row->titel";  if ("$row->level" <> "0") {echo " --Level $row->level --";} ?></b></span></td>
               	<td colspan="3" style="padding-bottom: 1px;" height="22" valign="bottom" width="100%"><span> <?
                 if (isset($get['delet']))
                 {
if ($right[$level]['newsedit'])
{
//echo '<a href="sites/newschange.php?newsid='.$row->ID.'">News Bearbeiten</a> ';
echo '<a href="index.php?lbsite=newschange&vars='.$row->ID.'" class="lbOn">Bearbeiten</a> ';
}    
if ($right[$level]['newsdel'])
{
echo '<a href="index.php?lbsite=delnews&vars='.$row->ID.'" class="lbOn">L�schen</a> ';
}    
}         
                   ?> </span></td>
               </tr>
               <tr>   
                  <td valign="top" height="22"><span><?="$row->datum"?> <?=$lang->word('by')?> <a href=index.php?site=user&show=<?="$row->ersteller"?>><?="$row->ersteller"?></a></span></td>

                  <td valign="top" align="right" height="22">
                  <?php if (file_exists("sites/comments.php")) { ?>
                  <a href="index.php?site=comments&id=<?="$row->ID"?>"><?=$lang->word('comments')?> <?   
                            
                  $abfrage2 = "SELECT * FROM ".$dbpr�fix."kommentar WHERE `zuid`= '$row->ID'";
                  
$ergebnis2 = $hp->mysqlquery($abfrage2);
    
 $nummer = 0; 
while($row2 = mysql_fetch_object($ergebnis2))
   { 
$nummer=$nummer+1;
    }
      echo " ($nummer)";            
                  
                  } ?>
   </a>
                  </td>
               </tr>
               </table>         
            </th>         
      
      </tr>

      
      <tr>
      	<td class="lcell"><span><?="$row->text"?></span></td>

      </tr>
      
      </table><br>      
<? }  }   

if (isset($get['delet']))
if ((!$right[$level]['newswrite']) and (!$right[$level]['newsedit']) and (!$right[$level]['newsdel']))
{
echo $lang->word('noright');

} else


{
/*
?>
<form method="POST" action="index.php?site=admin">
  <p>ID: <input type="text" name="newsiddel" size="3"><input type="submit" value="<?=$lang->word('delet')?>" name="newsdel"></p>
  </form>
<form method="GET" action="sites/newschange.php">
  <p>ID: <input type="text" name="newsid" size="3"><input type="submit" value="<?=$lang->word('edit')?>" name="newschange">
 
</form>


<?
*/
} else
{
?>
<p align="left"><font size="3"><a href="index.php?site=news&limit=20"><b><?=$lang->word('morenews')?></b></a></font></p>
<?}?>
<!--News Ende-->
<?
ob_end_flush();

?>
