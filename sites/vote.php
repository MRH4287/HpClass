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
$lbs = $hp->lbsites;
$xajaxF = $hp->xajaxF;


if (!$right[$level]['manage_vote'])
{
echo "Sie dürfen keine Umfragen Managen!<br>";
echo "<a href=index.php>Zurück</a>";
$error->error("Sie dürfen keine Newsmeldungen Managen!");
} else
{


if (isset($get['addvote']))
{

?>
<form action="index.php?site=vote" method="post">
<table width="100%" border="0">
  <tr>
    <td width="5%">&nbsp;</td>
    <td width="14%"><strong>Neue Umfrage starten:</strong></td>
    <td width="15%"></td>
  </tr>
  <tr>
    <td>Titel</td>
    <td><input type="text" name="titel" id="titel"  size="70"  onchange="checkvote(false)"/></td>
    <td><div id="titelwarn">Bitte Geben Sie einen Titel ein.</div></td>
  </tr>
  <tr>
    <td>Antworten:</td>
    <td><table width="100%" border="0" id="answers">
      <tr>
        <td>
            <input type="text" name="antwort[]" id="antwort1" size="70"  onchange="checkvote(false)" /> </td>
      </tr>
        <tr>
        <td>
            <input type="text" name="antwort[]" id="antwort2" size="70"  onchange="checkvote(false)" /> </td>
      </tr>
      
    </table>
    
    <!--Für die mit IE-->
    <div id="mehrantworten">
    <table width="100%" border="0" id="answers">
      <tr>
        <td>
            <input type="text" name="antwort[]"  size="70"  /> </td>
      </tr>
        <tr>
        <td>
            <input type="text" name="antwort[]"  size="70"   /> </td>
      </tr>
      
    </table>
    </div>
    
    
         <SCRIPT language="JavaScript">
<!--
var BrowserName = navigator.appName;
var antworten = document.getElementById("mehrantworten");

if (BrowserName!="Microsoft Internet Explorer")
{
antworten.style.visibility = 'hidden';
antworten.style.position = 'absolute';
antworten.style.top = '0px';
antworten.style.left = '0px';

}
//-->
</script>
    
    <img src="images/add.gif"  onClick="addanswer()"><hr /></td>
    <td><div id="answerwarn">Bitte Geben Sie mindestens 2 Antworten an.</div></td>
  </tr>
  <tr>
    <td>Gültig bis:</td>
    <td>
    <?
  
  $date = getdate();



$tag = $date['mday'];
$monat = $date['mon'];
$year = $date['year'];

if ($tag < 10)
{
$tag = "0".$tag;
}
if ($monat < 10)
{
$monat = "0".$monat;
}
  
  
  ?>
  
    <input type="hidden" name="day" id="day" size="2" maxlength="2" value="<?=$tag?>" onchange="checkvote(false)"  />
       <input type="hidden" name="month" id="month" size="2" maxlength="2" value="<?=$monat?>" onchange="checkvote(false)" /> 
      <input type="hidden" name="year" id="year" size="4" maxlength="4" value="<?=$year?>"  onchange="checkvote(false)"/> 
       <input type="hidden" name="hour" id="hour" size="2" maxlength="2" value="00"  onchange="checkvote(false)"/>
        <input type="hidden" name="min" id="min" size="2" maxlength="2" value="00"  onchange="checkvote(false)"/>
        <div id="kalender" align="center"></div></td>
      <td><div id="datewarn">Überprüfen Sie die Eingaben</div>
      <br>Aktuelle Auswahl: <div id="aktdate"><? echo "$tag.$monat.$year"; ?></div></td>
  
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div id="post">Bitte beachten Sie die Meldungen</div>
    <noscript><br>Überprüfung deaktiviert:<br><input type="submit" name="addvote" id="post" value="Senden" /></noscript></td> <!--<input type="submit" name="button" id="button" value="Senden" />-->
    <td></td>
  </tr>
</table>
</form>
<?
$xajaxF->open("xajax_calender();");

} elseif (isset($post['addvote']))
{
$titel = $post['titel'];
$antwort = $post['antwort'];
$day = $post['day'];
$month = $post['month'];
$year = $post['year'];
$hour = $post['hour'];
$min = $post['min'];
$user = $_SESSION['ID'];
$time = time();

$timestamp = mktime($hour, $min, 0, $month, $day, $year);
$antworten = "";

foreach ($antwort as $key=>$value) {
	
if ($value != "")
{
if ($antworten == "")
{
$antworten = "$value";
} else
{
$antworten .= "<!--!>$value";
}

}

}


$sql = "INSERT INTO `$dbpräfix"."vote` (
`ID` ,
`name` ,
`userid` ,
`antworten` ,
`ergebnisse` ,
`timestamp` ,
`upto`
)
VALUES (
NULL , '$titel', '$user', '$antworten', '', '$time', '$timestamp'
);
";
$erg = $hp->mysqlquery($sql);
//$this->fp->log($sql);

$info->okn("Umfrage erfolgreich eingetragen!");
echo "Erfolreich eingetragen<br><a href=index.php?site=vote>Zurück</a>";


} elseif (isset($get['editvote']))
{
$id = $get['editvote'];

$sql = "SELECT * FROM `$dbpräfix"."vote` WHERE `ID` = $id";
$erg = $hp->mysqlquery($sql);
$row = mysql_fetch_object($erg);

?>
<form action="index.php?site=vote" method="post">
<input type="hidden" value="<?=$id?>" name="ID">
<table width="100%" border="0">
  <tr>
    <td width="5%">&nbsp;</td>
    <td width="14%"><strong>Neue Umfrage starten:</strong></td>
    <td width="15%"></td>
  </tr>
  <tr>
    <td>Titel</td>
    <td><input type="text" name="titel" id="titel"  size="70" value="<?=$row->name?>"  onchange="checkvote(true)" /></td>
    <td><div id="titelwarn">Bitte Geben Sie einen Titel ein.</div></td>
  </tr>
  <tr>
    <td>Antworten:</td>
    <td><table width="100%" border="0" id="answers">
    <?
    
    $antworten = explode("<!--!>", $row->antworten);
    $i = 0;
    foreach ($antworten as $key=>$value) {
    	$i++;
    
    ?>
      <tr>
        <td>
            <input type="text" name="antwort[]" id="antwort<?=$i?>" size="70"  onchange="checkvote(true)" value="<?=$value?>" /> </td>
      </tr>

      <?
      }
      ?>
    </table><img src="images/add.gif"  onClick="addanswer()"><hr /></td>
    <td><div id="answerwarn">Bitte Geben Sie mindestens 2 Antworten an.</div></td>
  </tr>
  <tr>
    <td>Gültig bis:</td>
    <td>
    <?
  
  $date = getdate($row->upto);



$tag = $date['mday'];
$monat = $date['mon'];
$year = $date['year'];

if ($tag < 10)
{
$tag = "0".$tag;
}
if ($monat < 10)
{
$monat = "0".$monat;
}
  
  
  ?>
    <input type="hidden" name="day" id="day" size="2" maxlength="2" value="<?=$tag?>" onchange="checkvote(true)"  />
       <input type="hidden" name="month" id="month" size="2" maxlength="2" value="<?=$monat?>" onchange="checkvote(true)" /> 
      <input type="hidden" name="year" id="year" size="4" maxlength="4" value="<?=$year?>"  onchange="checkvote(true)"/> 
       <input type="hidden" name="hour" id="hour" size="2" maxlength="2" value="00"  onchange="checkvote(true)"/>
        <input type="hidden" name="min" id="min" size="2" maxlength="2" value="00"  onchange="checkvote(true)"/>
        <div id="kalender" align="center"></div></td>
      <td><div id="datewarn">Überprüfen Sie die Eingaben</div>
      <br>Aktuelle Auswahl: <div id="aktdate"><? echo "$tag.$monat.$year"; ?></div></td>
  
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div id="post">Bitte beachten Sie die Meldungen</div>
    <noscript><br>Überprüfung deaktiviert:<br><input type="submit" name="editvote" id="post" value="Senden" /></noscript></td> <!--<input type="submit" name="button" id="button" value="Senden" />-->
    <td></td>
  </tr>
</table>
</form>
<?
$xajaxF->open("xajax_calender();");
$xajaxF->open("checkvote(true);");




} elseif (isset($post['editvote']))
{
$ID = $post['ID'];
$titel = $post['titel'];
$antwort = $post['antwort'];
$day = $post['day'];
$month = $post['month'];
$year = $post['year'];
$hour = $post['hour'];
$min = $post['min'];
$user = $_SESSION['ID'];
$time = time();

$timestamp = mktime($hour, $min, 0, $month, $day, $year);
$antworten = "";

foreach ($antwort as $key=>$value) {
	
if ($value != "")
{
if ($antworten == "")
{
$antworten = "$value";
} else
{
$antworten .= "<!--!>$value";
}

}

}


$sql = "UPDATE `$dbpräfix"."vote` SET `name` = '$titel', `antworten` = '$antworten', `upto` = '$timestamp' WHERE `ID` = '$ID';";
$erg = $hp->mysqlquery($sql);


$info->okn("Umfrage erfolgreich aktualisiert!");
echo "Erfolreich aktualisiert<br><a href=index.php?site=vote>Zurück</a>";



} elseif (isset($post['delvote']))
{
$sql = "DELETE FROM `$dbpräfix"."vote` WHERE `ID` = ".$post['voteiddel'];
$erg = $hp->mysqlquery($sql);

$info->ok("Umfrage erfolgreich gelöscht!");
echo "Umfrage erfolgreich gelöscht.<br><a href=index.php?site=vote>Zurück</a>";


} else
{

echo "Das Menu :D";
echo "<br> Hier werden dann die Votes angezeigt.";
echo "<br><a href=index.php?site=vote&addvote>Neue Umfrage</a><br><hr>";

$sql = "SELECT * FROM `$dbpräfix"."vote`";
$erg = $hp->mysqlquery($sql);
while ($row = mysql_fetch_object($erg))
{
$ergebniss = explode("<!--!>", $row->ergebnisse);
$voted = count($ergebniss);
if ($ergebniss[0] == "")
{
$voted--;
}
$whov = explode("<!--!>", $row->voted);
?>

<table width="251" border="1">
   <tr>
    <td><strong><?=$row->name?></strong></td>
  </tr>
  <tr>
    <td>
    <div id="ergebnisse<?=$row->ID?>">
    <?
    
   
   if ($row->upto > time())
    {
    
   if (!in_array($_SESSION['ID'], $whov))
   { 
    ?>
    
    
    <div id="voteok<?=$row->ID?>">
      <table width="200">
      <?
      $answers = explode("<!--!>", $row->antworten);
    
      foreach ($answers as $key=>$value) {
      
      ?>
        <tr>
          <td><label  onclick="setvote(<?=$key?>, '<?=$row->ID?>');">
            <input type="radio" name="answer" value="<?=$row->ID?>"/>
            <?=$value?></label></td>
        </tr>
        <?
        }
        ?>
    </table>      
      <p>
      <? if (isset($_SESSION['ID'])) { ?>
        <input type="submit" name="button" id="button" value="Senden" onclick="postvote('<?=$row->ID?>');" />
        <input type="hidden" name="vote" id="vote<?=$row->ID?>" />
        <? } else { echo "<b>Zum Abstimmen müssen Sie sich einloggen!</b>"; } ?>
      </p>
      </div>
      <?
      } else
      {
      echo "<br><center><img src=images/alert.gif><br>Bereits abgestimmt</center><br>";
      }
      
      } else
      {
      echo "<br><center><img src=images/alert.gif><br>Abstimmung abgelaufen</center><br>";
      }
      ?>
      
      <p>Bereits <strong> <?=$voted?> </strong>Stimmen abgegeben.<br />
     <input type="button" onclick="xajax_vote_result(<?=$row->ID?>)" value="Ergebnisse"></p>
     
      </div>
      
    </td>
  </tr>
  <?
  if ($right[$level]['manage_vote'])
  {
  ?>
  <tr>
   <td>
   <a href="index.php?site=vote&editvote=<?=$row->ID?>">Bearbeiten</a> \ <? echo $lbs->link("delvote", "Löchen", $row->ID); ?>
   </td> 
  </tr>
  <?
  }
  ?> 
  
</table>
<?
}

}

} // Right
?>