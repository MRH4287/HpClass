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

if (isset($post['changelevel'])) 
{
$leveltemp=$post['level'];
$eingabe = "UPDATE `".$dbpr�fix."user` SET `level` = '$leveltemp' WHERE `user` = '".$get['change']."';";
$ergebnis = $hp->mysqlquery($eingabe);
echo mysql_error();
}

if (isset ($get['delet']))
{
if ($_SESSION['level'] !== "3") { echo $lang->word('noright'); exit; }
$eingabe = "DELETE FROM `".$dbpr�fix."user` WHERE `user` = '".$get['delet']."';";
$ergebnis = $hp->mysqlquery($eingabe);
echo mysql_error();
if ($ergebnis==true) {
echo $lang->word('delok');
} 
}

if (!isset($get['show'])) {

$abfrage = "SELECT * FROM ".$dbpr�fix."user ORDER BY `level` DESC ";
$ergebnis = $hp->mysqlquery($abfrage)
    OR die("Error: $abfrage <br>".mysql_error());

?>


<p align="center"><font size="5">User:</font></p>
<p align="center">&nbsp;</p>
<p align="center"><table border="0" width="600">

  <tr>
  

    <td width="10" bgcolor="<?=$defaultcolor?>">
      <p align="center"><?=$lang->word('username')?>:</p>
    </td>
    <td width="10" bgcolor="<?=$defaultcolor?>">
      <p align="center"><?=$lang->word('since')?>:</p>
    </td>
    <td width="10" bgcolor="<?=$defaultcolor?>">
      <p align="center"><?=$lang->word('name')?>:</p>
    </td>
    <td width="10" bgcolor="<?=$defaultcolor?>">
      <p align="center"><?=$lang->word('nachname')?>:</p>
    </td>

  </tr>
 

<?   
while($row = mysql_fetch_object($ergebnis))
   {
 
?>

  <tr>

  
    <td width="10"><p align="center"><a href=index.php?site=user&show=<?="$row->user"?>><?="$row->user"?></a></p></td>
    <td width="10"><p align="center"><?="$row->datum"?></p></td>
    <td width="10"><p align="center"><?="$row->name"?></p></td>
    <td width="10"><p align="center"><?="$row->nachname"?></p></td>
    

  </tr>

<?
}
?>
</table> </p>

<?



} else
{
$abfrage = "SELECT * FROM ".$dbpr�fix."user WHERE `user` = '".$get['show']."'";

$ergebnis = $hp->mysqlquery($abfrage)
 OR die("Error: $abfrage <br>".mysql_error());
while($row = @mysql_fetch_object($ergebnis))
   {
?>

<table>
  <tr>
    <th style="text-align: center; padding-right: 2px;" width="300">Profil</th>
  </tr>
<td>
</td>

  <tr>
    <td style="padding-right: 4px;" valign="top">
    <div id="page1">
      <table class="liste" width="100%">
        
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?=$lang->word('avatar')?></th>
            <td style="padding: 2px;"><img src="include/userpics.php?id=<?="$row->ID"?>" ></td>
          </tr>       
        
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?=$lang->word('nick')?></th>
            <td style="padding: 2px;"><?="$row->user"?></td>
          </tr>
             <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('name')?></th>
            <td style="padding: 2px;"><?="$row->name"?> <?="$row->nachname"?></td>
          </tr>
          <? /* ?>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('rank')?></th>
            <td style="padding: 2px;"><?  
    if ("$row->level" == "1") { echo "User"; } 
    if ("$row->level" == "2") { echo "Moderator"; } 
    if ("$row->level" == "3") { echo "Administrator"; }  
    ?></td>
          </tr>
          <? */ ?>

          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('mail')?></th>
            <td style="padding: 2px;"><a href=mailto://<?="$row->email"?>><?="$row->email"?></a></td>
          </tr>

          <tr>
            <th style="padding-bottom: 6px;"><b><?=$lang->word('alter')?></b></th>
            <td style="padding: 2px;"><?="$row->alter"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('wohnort')?></th>
            <td style="padding: 2px;"><?="$row->wohnort"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('birthday')?></th>
            <td style="padding: 2px;"><?="$row->geburtstag"?></td>
          </tr>

          </table>
          
          </div>
          <div id="page2" style="display:none">
          
          <table class="liste" width="100%">
             <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('clan')?></th>
            <td style="padding: 2px;"><?="$row->clan"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('clantag')?></th>
            <td style="padding: 2px;"><?="$row->clantag"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('clanhp')?></th>
            <td style="padding: 2px;"><?="$row->clanhomepage"?></td>
          </tr>       
               <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('clanhist')?></th>
            <td style="padding: 2px;"><?="$row->clanhistory"?></td>
          </tr> 
          </table>
          
         </div> 
         <div id="page3" style="display:none">
         
         <table class="liste" width="100%">
                   <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('lastlogin')?>:</th>
            <td style="padding: 2px;"><?
            $timeuser = (int) "$row->lastlogin";
            if ($timeuser != 0)
            {
             echo date("d.m.Y H:i s", $timeuser); 
             } else
             {
             echo $lang->word('never');
             } ?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"></th>
            <td style="padding: 2px;"><b><a href=index.php?site=pm&new&to=<?="$row->user"?>><?=$lang->word('pm')?></a></b></td>
          </tr>   

           <tr>
            <th style="padding-bottom: 6px;"></th>
            <td style="padding: 2px;"></td>
          </tr>        
            <tr>
          <?
            if ($right[$level]['userchangelevel']) { ?>
            <th style="padding-bottom: 6px;">Level:</th>
            <td style="padding: 2px;">
            <?
            if (!$right[$level]['userchangelevel']) {
            echo "$row->level";
            } else {
            ?>
            
<form method="POST" action="index.php?site=user&change=<?=$get['show']?>&show=<?=$get['show']?>">
<select size="3" name="level">
        <option <? if ("$row->level" == 1) { echo "selected"; } ?>>1</option>
        <option <? if ("$row->level" == 2) { echo "selected"; } ?>>2</option>
        <option <? if ("$row->level" == 3) { echo "selected"; } ?>>3</option>
      </select><!--<input type="text" name="level" size="3" value="<?="$row->level"?>">-->
      </td>
            <th style="padding-bottom: 6px;"></th>
            <td style="padding: 2px;"><input type="submit" value="<?=$lang->word('send')?>" name="changelevel">
</form></td>
</tr>
<?
            }
            }
            if ($right[$level]['userdelet']) {
            ?>
            
          <tr>
            <th style="padding-bottom: 6px;">L�schen</th>
            <td style="padding: 2px;"><a href=index.php?site=user&delet=<?=$get['show']?>><?=$lang->word('delet')?></a></td>
            
          </tr> 
            <?
           } 
            
            ?> 
         </table>
         </div> 



          

           

          </td>      
            
          </tr> 
          
          
          
        
      
    </td>
  </tr>
</table>
 <tr>
          <script type="text/javascript">
var page1 = document.getElementById('page1');
var page2 = document.getElementById('page2');
var page3 = document.getElementById('page3');

</script> 
            
<a href="#" onclick="page1.style.display = ''; page2.style.display = 'none'; page3.style.display = 'none';"><?=$lang->word('page')?>1</a> 
<a href="#" onclick="page2.style.display = ''; page1.style.display = 'none'; page3.style.display = 'none';"><?=$lang->word('page')?>2</a> 
<a href="#" onclick="page3.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none';"><?=$lang->word('page')?>3</a> 
 



<? } }
?>
