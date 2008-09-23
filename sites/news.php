
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
$dbpräfix = $hp->getpräfix();
$lang = $hp->getlangclass();
$error = $hp->geterror();


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
   if (!isset($get['delet'])) {
   ?>
   <a name="n<?="$row->ID"?>">
   <? } ?>
      <table class="liste" width="100%" border="1" bordercolor="#000000" bordercolorlight="#000000">
      <tr>
      
            <th bgcolor="<?=$defaultcolor?>">

               <table width="100%" cellpadding="0" height="48">
               <tr>
               	<td rowspan="2" height="46"><img src="images/news_<?="$row->typ"?>.gif" width="40" height="40"></td>
               	<td width="100%" colspan="2" valign="bottom" style="padding-bottom: 1px" height="22"><span><b><?if (isset($get['delet'])) {echo"ID: $row->ID Titel: ";} echo"$row->titel";  if ("$row->level" <> "0") {echo " --Level $row->level --";} ?></b></span></td>
               </tr>
               <tr>   
                  <td valign="top" height="22"><span><?="$row->datum"?> <?=$lang->word('by')?> <a href=index.php?site=user&show=<?="$row->ersteller"?>><?="$row->ersteller"?></a></span></td>

                  <td valign="top" align="right" height="22">
                  <a href="index.php?site=comments&id=<?="$row->ID"?>"><?=$lang->word('comments')?> <?   
                            
                  $abfrage2 = "SELECT * FROM ".$dbpräfix."kommentar WHERE `zuid`= '$row->ID'";
                  
$ergebnis2 = $hp->mysqlquery($abfrage2);
    
 $nummer = 0; 
while($row2 = mysql_fetch_object($ergebnis2))
   { 
$nummer=$nummer+1;
    }
      echo " ($nummer)";            
                   ?>
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

?>
<form method="POST" action="index.php?site=admin">
  <p>ID: <input type="text" name="newsiddel" size="3"><input type="submit" value="<?=$lang->word('delet')?>" name="newsdel"></p>
  </form>
<form method="GET" action="sites/newschange.php">
  <p>ID: <input type="text" name="newsid" size="3"><input type="submit" value="<?=$lang->word('edit')?>" name="newschange">
 
</form>


<?
} else
{
?>
<p align="left"><font size="3"><a href="index.php?site=news&limit=20"><b><?=$lang->word('morenews')?></b></a></font></p>
<?}?>
<!--News Ende-->
<?
ob_end_flush();

?>
