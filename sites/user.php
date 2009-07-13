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

if (isset($post['changelevel'])) 
{
$leveltemp=$post['level'];
$eingabe = "UPDATE `".$dbpräfix."user` SET `level` = '$leveltemp' WHERE `user` = '".$get['change']."';";
$ergebnis = $hp->mysqlquery($eingabe);
echo mysql_error();
}

if (isset ($get['delet']))
{
$hp->info->info("Den User ".$get['delet']." Wirklich endgültig löschen? <a href=index.php?site=user&delet2=".$get['delet'].">Ja</a>");
}

if (isset ($get['delet2']))
{
if (!$right[$level]['userdelet']) { echo $lang->word('noright'); exit; }
$eingabe = "DELETE FROM `".$dbpräfix."user` WHERE `user` = '".$get['delet2']."';";
$ergebnis = $hp->mysqlquery($eingabe);
echo mysql_error();
if ($ergebnis==true) {
echo $lang->word('delok');
} 
}

if (!isset($get['show'])) {

$abfrage = "SELECT * FROM ".$dbpräfix."user ORDER BY `level` DESC ";
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
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `user` = '".$get['show']."'";

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
        <?  ?>
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?=$lang->word('avatar')?></th>
            <td style="padding: 2px;"><img src="include/userpics.php?id=<?="$row->ID"?>" ></td>
          </tr>       
        <?  ?>
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?=$lang->word('nick')?></th>
            <td style="padding: 2px;"><?="$row->user"?></td>
          </tr>
             <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('name')?></th>
            <td style="padding: 2px;"><?="$row->name"?> <?="$row->nachname"?></td>
          </tr>
          
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('rank')?></th>
            <td style="padding: 2px;"><? 
            $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` = $row->level";
            $erg2 = $hp->mysqlquery($sql);
            $row2 = mysql_fetch_object($erg2);
            echo $row2->name;
            
    ?></td>
          </tr>
          
<? if ($right[$level]['see_email'])
{ ?>
          <tr>
            <th style="padding-bottom: 6px;"><?=$lang->word('mail')?></th>
            <td style="padding: 2px;"><a href=mailto://<?="$row->email"?>><?="$row->email"?></a></td>
          </tr>
<?
}

?>
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
<?  ?>

          </table>
          
          </div>
          <?  ?>
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
         <?  ?>
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
<?

$sql = "SELECT * FROM `".$dbpräfix."right`";
$erg = $hp->mysqlquery($sql);
$levels = array();
while ($row = mysql_fetch_object($erg))
{
if (!in_array($row->level, $levels))
{
$levels[] = $row->level;
}
}

?>

<select size="<?=count($levels)?>" name="level">
<?

$fp = $hp->fp;
$fp->log($levels);
foreach ($levels as $egal=>$aktlevel) {

?>

        <option <? if ("$row->level" == aktlevel) { echo "selected"; } ?>><?=$aktlevel?></option>
        <?
        } ?>

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
            <th style="padding-bottom: 6px;">Löschen</th>
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
 
          <script type="text/javascript">
var page1 = document.getElementById('page1');
var page2 = document.getElementById('page2');
var page3 = document.getElementById('page3');

</script> 
            
<a href="#" onclick="page1.style.display = ''; page2.style.display = 'none'; page3.style.display = 'none';">User</a> 
<?  ?><a href="#" onclick="page2.style.display = ''; page1.style.display = 'none'; page3.style.display = 'none';">Claninfo</a> <?  ?>
<a href="#" onclick="page3.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none';">System</a> 
 



<? } }
?>
