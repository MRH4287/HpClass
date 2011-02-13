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
  

    <td width="10" bgcolor="<?php echo $defaultcolor?>">
      <p align="center"><?php echo $lang->word('username')?>:</p>
    </td>
    <td width="10" bgcolor="<?php echo $defaultcolor?>">
      <p align="center"><?php echo $lang->word('since')?>:</p>
    </td>
    <td width="10" bgcolor="<?php echo $defaultcolor?>">
      <p align="center"><?php echo $lang->word('name')?>:</p>
    </td>
    <td width="10" bgcolor="<?php echo $defaultcolor?>">
      <p align="center"><?php echo $lang->word('nachname')?>:</p>
    </td>

  </tr>
 

<?php   
while($row = mysql_fetch_object($ergebnis))
   {
 
?>

  <tr>

  
    <td width="10"><p align="center"><a href=index.php?site=user&show=<?php echo "$row->user"?>><?php echo "$row->user"?></a></p></td>
    <td width="10"><p align="center"><?php echo "$row->datum"?></p></td>
    <td width="10"><p align="center"><?php echo "$row->name"?></p></td>
    <td width="10"><p align="center"><?php echo "$row->nachname"?></p></td>
    

  </tr>

<?php
}
?>
</table> </p>

<?php



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
        <?php  ?>
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?php echo $lang->word('avatar')?></th>
            <td style="padding: 2px;"><img src="include/userpics.php?id=<?php echo "$row->ID"?>" ></td>
          </tr>       
        <?php  ?>
          <tr>
            <th style="padding-bottom: 6px;" width="100"><?php echo $lang->word('nick')?></th>
            <td style="padding: 2px;"><?php echo "$row->user"?></td>
          </tr>
             <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('name')?></th>
            <td style="padding: 2px;"><?php echo "$row->name"?> <?php echo "$row->nachname"?></td>
          </tr>
          
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('rank')?></th>
            <td style="padding: 2px;"><?php 
            $sql = "SELECT * FROM `$dbpräfix"."ranks` WHERE `level` = $row->level";
            $erg2 = $hp->mysqlquery($sql);
            $row2 = mysql_fetch_object($erg2);
            echo $row2->name;
            
    ?></td>
          </tr>
          
<?php if ($right[$level]['see_email'])
{ ?>
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('mail')?></th>
            <td style="padding: 2px;"><a href=mailto://<?php echo "$row->email"?>><?php echo "$row->email"?></a></td>
          </tr>
<?php
}

?>
          <tr>
            <th style="padding-bottom: 6px;"><b><?php echo $lang->word('alter')?></b></th>
            <td style="padding: 2px;"><?php echo "$row->alter"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('wohnort')?></th>
            <td style="padding: 2px;"><?php echo "$row->wohnort"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('birthday')?></th>
            <td style="padding: 2px;"><?php echo "$row->geburtstag"?></td>
          </tr>
<?php  ?>

          </table>
          
          </div>
          <?php  ?>
          <div id="page2" style="display:none">
          
          <table class="liste" width="100%">
             <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('clan')?></th>
            <td style="padding: 2px;"><?php echo "$row->clan"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('clantag')?></th>
            <td style="padding: 2px;"><?php echo "$row->clantag"?></td>
          </tr>
          <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('clanhp')?></th>
            <td style="padding: 2px;"><?php echo "$row->clanhomepage"?></td>
          </tr>       
               <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('clanhist')?></th>
            <td style="padding: 2px;"><?php echo "$row->clanhistory"?></td>
          </tr> 
          </table>
          
         </div> 
         <?php  ?>
         <div id="page3" style="display:none">
         
         <table class="liste" width="100%">
                   <tr>
            <th style="padding-bottom: 6px;"><?php echo $lang->word('lastlogin')?>:</th>
            <td style="padding: 2px;"><?php
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
            <td style="padding: 2px;"><b><a href=index.php?site=pm&new&to=<?php echo "$row->user"?>><?php echo $lang->word('pm')?></a></b></td>
          </tr>   

           <tr>
            <th style="padding-bottom: 6px;"></th>
            <td style="padding: 2px;"></td>
          </tr>        
            <tr>
          <?php
            if ($right[$level]['userchangelevel']) { ?>
            <th style="padding-bottom: 6px;">Level:</th>
            <td style="padding: 2px;">
            <?php
            if (!$right[$level]['userchangelevel']) {
            echo "$row->level";
            } else {
            ?>
            
<form method="POST" action="index.php?site=user&change=<?php echo $get['show']?>&show=<?php echo $get['show']?>">
<?php

$sql = "SELECT * FROM `".$dbpräfix."right`";
$erg2 = $hp->mysqlquery($sql);
$levels = array();
while ($row2 = mysql_fetch_object($erg2))
{
if (!in_array($row2->level, $levels))
{
$levels[] = $row2->level;
}
}
sort($levels);

?>

<select size="<?php echo count($levels)?>" name="level">
<?php

$fp = $hp->fp;
$fp->log($levels);
foreach ($levels as $egal=>$aktlevel) {

?>

        <option <?php if ($row->level == $aktlevel) { echo "selected"; } ?>><?php echo $aktlevel?></option>
        <?php
        } ?>

      </select><!--<input type="text" name="level" size="3" value="<?php echo "$row->level"?>">-->
      </td>
            <th style="padding-bottom: 6px;"></th>
            <td style="padding: 2px;"><input type="submit" value="<?php echo $lang->word('send')?>" name="changelevel">
</form></td>
</tr>
<?php
            }
            }
            if ($right[$level]['userdelet']) {
            ?>
            
          <tr>
            <th style="padding-bottom: 6px;">Löschen</th>
            <td style="padding: 2px;"><a href=index.php?site=user&delet=<?php echo $get['show']?>><?php echo $lang->word('delet')?></a></td>
            
          </tr> 
            <?php
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
<?php  ?><a href="#" onclick="page2.style.display = ''; page1.style.display = 'none'; page3.style.display = 'none';">Claninfo</a> <?php  ?>
<a href="#" onclick="page3.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none';">System</a> 
 



<?php } }
?>
