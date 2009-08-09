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





if (!isset($_SESSION['username'])) {
$error->error($lang->word("error-login-profil") ,"2");
exit;
}
if (!isset ($post['pwändern']) and !isset ($post['go']) and !isset($post['pwneu']))
{
$abfrage = "SELECT * FROM ".$dbpräfix."user WHERE `user` = '".$_SESSION['username']."'";
$ergebnis = $hp->mysqlquery($abfrage);
    
while($row = mysql_fetch_object($ergebnis))
   {
?>

 


<form method="POST" action="index.php?site=profil">
<div id="page1">
  <table border="0" width="318">
    <tr>
      <td width="300" colspan="2">
        <p align="center"><?=$lang->word("page-intern")?>:</td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('username')?>:</td>
      <td width="175"><?=$_SESSION['username']?></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('password')?>:</td>
      <td width="175"><input type="submit" value="<?=$lang->word('changepw')?>" name="pwändern"></td>
      </form>
      
    </tr>
        <tr>
      <td width="125"><?=$lang->word('avatar')?>:</td>
      <td width="175">
      <?
      // Einbinden des Bildes!!
/*
  //$aImage = array("include/userpics.php?id=$row->ID");
//$iMaxW  = 140;     // maximale Breite
//$iMaxH  = 105;      // maximale Höhe

    // $_SESSION['bilduserid'] = "1"; 
//foreach ($aImage as $sImage)
//{

// $image = imagecreatefromjpeg("include/userpics.php");
 
   // $aSize = getimagesize();



    $iWidth = imagesx($image);
    $iHeight = magesy($image);
    if (isset($iWidth))
    {
    $iRatioW = $iMaxW / $iWidth;
    }
     if (isset($iHeight))
    {
    $iRatioH = $iMaxH / $iHeight;
    }
    if ($iRatioW < $iRatioH)
    {
        $iNewW = $iWidth * $iRatioW;
        $iNewH = $iHeight * $iRatioW;
        $sRatio = substr($iRatioW, 0, 5);
        $sRatioE = 'Breite';
    } else {
        $iNewW = $iWidth * $iRatioH;
        $iNewH = $iHeight * $iRatioH;
        $sRatio = substr($iRatioH, 0, 5);
        $sRatioE = 'H&ouml;he';
    } // end if

  
 // echo '<img border="0" src="include/userpics.php?id='.$row->ID.'" width="'.$iNewW.'" height="'.$iNewH.'">';
}
*/
echo '<img border="0" src="include/userpics.php?id='."$row->ID".'" >';
      

      
      
      ?>
      

      
      	<form enctype="multipart/form-data" action="index.php?site=userpicchange&id=<?="$row->ID"?>" method="post">
		<input name="FILE" type="file">
		<input type="hidden" name="MAX_FILE_SIZE" value="30000">
		<input type="submit" value="<?=$lang->word('upload-picture')?>" name="sendfiles" />
	</form>
      
      </td>
      
      
    </tr>
    <form method="POST" action="index.php?site=profil">
    <tr>
      <td width="125"></td>
      <td width="175"></td>
    </tr>
  </table>
  </div>
  <div id="page2" style="display:none">
  <table border="0" width="318">
    <tr>
      <td width="300" colspan="2">
        <p align="center"><?=$lang->word('persönliches')?>:</td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('name')?>: </td>
      <td width="175"><input  name="name" size="24" value="<?="$row->name"?>" ></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('nachname')?>: </td>
      <td width="175"><input  name="nachname" size="24" value="<?="$row->nachname"?>"></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('alter')?>:</td>
      <td width="175"><input  name="alter" size="24" value="<?="$row->alter"?>" ></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('birthday')?>:</td>
      <td width="175"><input  name="geburtstag" size="24" value="<?="$row->geburtstag"?>" ></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('wohnort')?>:</td>
      <td width="175"><input  name="wohnort" size="24" value="<?="$row->wohnort"?>" ></td>
    </tr>
        <tr>
      <td width="125"><?=$lang->word('telephone')?>:</td>
      <td width="175"><input  name="tel" size="24" value="<?="$row->tel"?>" ></td>
    </tr>
        <tr>
      <td width="125"><?=$lang->word('email')?>:</td>
      <td width="175"><?="$row->email"?></td>
    </tr>
    <tr>
      <td width="125"></td>
      <td width="175"></td>
    </tr>
    <tr>
      <td width="300" colspan="2">
     <!--   <p align="center"><input type="submit" value="<?=$lang->word('ok')?>" name="go"></td> -->
     <p align="center"> <button type="submit" name="go"><img src="images/ok.gif" alt="<?=$lang->word('ok')?>"></button>
     <button type="reset" name="go"><img src="images/abort.gif" alt="<?=$lang->word('delet')?>"></button></p></td>
     
     
    </tr>
    </table>
    </div>
    <div id="page3" style="display:none">
    <table border="0" width="318">
    
    <tr>
      <td width="300" colspan="2">
        <p align="center"><?=$lang->word('sonstiges')?>:</td>
    </tr>

    
    <tr>
      <td width="125"><?=$lang->word('cpu')?>:</td>
      <td width="175"><input type="text" name="cpu" value="<?="$row->cpu"?>" size="24" maxlength="30"></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('ram')?>:</td>
      <td width="175"><input type="text" name="ram" value="<?="$row->ram"?>" size="24" maxlength="30"></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('graka')?>:</td>
      <td width="175"><input type="text" name="graka" value="<?="$row->graka"?>" size="24" maxlength="30"></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('hdd')?>:</td>
      <td width="175"><input type="text" name="hdd" value="<?="$row->hdd"?>" size="24" maxlength="30"></td>
    </tr>
    
    <tr>
      <td width="125"></td>
      <td width="175"></td>
    </tr>
    <tr>
      <td width="300" colspan="2">
             <!--   <p align="center"><input type="submit" value="<?=$lang->word('ok')?>" name="go"></td> -->
     <p align="center"> <button type="submit" name="go"><img src="images/ok.gif" alt="<?=$lang->word('ok')?>"></button>
     <button type="reset" name="go"><img src="images/abort.gif" alt="<?=$lang->word('delet')?>"></button></p></td>
    </tr>
  </table>
  </div>
  <div id="page4" style="display:none">
     <table border="0" width="318">
    
    <tr>
      <td width="300" colspan="2">
        <p align="center"><?=$lang->word('clan')?>:</td>
    </tr>

    
    <tr>
      <td width="125"><?=$lang->word('clanname')?>:</td>
      <td width="175"><input type="text" name="clan" value="<?="$row->clan"?>" size="24" maxlength="30"></td>
    </tr>

    <tr>
      <td width="125"><?=$lang->word('clantag')?>:</td>
      <td width="175"><input type="text" name="clantag" value="<?="$row->clantag"?>" size="24" maxlength="30"></td>
    </tr>
    <tr>
      <td width="125"><?=$lang->word('clanhp')?>:</td>
      <td width="175"><input type="text" name="clanhomepage" value="<?="$row->clanhomepage"?>" size="24" maxlength="30"></td>
    </tr>
        <tr>
      <td width="125"><?=$lang->word('clanhist')?>:</td>
      <td width="175"><textarea rows="2" name="clanhistory" cols="40"><?="$row->clanhistory"?></textarea></td>
    </tr>
    
    <tr>
      <td width="125"></td>
      <td width="175"></td>
    </tr>
    <tr>
      <td width="300" colspan="2">
             <!--   <p align="center"><input type="submit" value="<?=$lang->word('ok')?>" name="go"></td> -->
     <p align="center"> <button type="submit" name="go"><img src="images/ok.gif" alt="<?=$lang->word('ok')?>"></button>
     <button type="reset" name="go"><img src="images/abort.gif" alt="<?=$lang->word('delet')?>"></button></p></td>
    </tr>
  </table>
  </div>

  
</form>

<script type="text/javascript">
var page1 = document.getElementById('page1');
var page2 = document.getElementById('page2');
var page3 = document.getElementById('page3');
var page4 = document.getElementById('page4');
//var page5 = document.getElementById('page5');


</script>




<input type="button" value="<?=$lang->word('start')?>" onclick="page1.style.display = ''; page2.style.display = 'none'; page3.style.display = 'none'; page4.style.display = 'none'; ">
<input type="button" value="<?=$lang->word('persönliches')?>" onclick="page2.style.display = ''; page1.style.display = 'none'; page3.style.display = 'none'; page4.style.display = 'none'; ">
<input type="button" value="<?=$lang->word('sonstiges')?>" onclick="page3.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none'; page4.style.display = 'none'; ">
<input type="button" value="<?=$lang->word('clan')?>" onclick="page4.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none'; page3.style.display = 'none'; ">
<? /* ?><input type="button" value="Team" onclick="page5.style.display = ''; page1.style.display = 'none'; page2.style.display = 'none'; page3.style.display = 'none'; page4.style.display = 'none';"><? */ ?>


<? } 
} else
{
//include("../include/config.php");


/*
$db = mysql_connect($dbserver,
$dbuser,$dbpass)
or print "keine Verbindung möglich. Benutzername oder Passwort sind falsch";
 
mysql_select_db($dbdatenbank, $db)
or print "Die Datenbank existiert nicht.";
*/

if (isset ($post['pwändern'])) {
?>
<form method="POST" action="index.php?site=profil">
  <table border="0" width="318">
    <tr>
      <td width="127"><?=$lang->word('oldpw')?>:</td>
      <td width="175"><input type="password" name="passwortalt" size="24" maxlength="20"></td>
    </tr>
    <tr>
      <td width="127"></td>
      <td width="175"></td>
    </tr>
    <tr>
      <td width="127"><?=$lang->word('newpw')?>:</td>
      <td width="175"><input type="password" name="passwort" size="24" maxlength="20"></td>
    </tr>
    <tr>
      <td width="127"><?=$lang->word('repnewpw')?></td>
      <td width="175"><input type="password" name="passwort2" size="24" maxlength="20"></td>
    </tr>
    <tr>
      <td width="302" colspan="2">
        <p align="center"><button type="submit" name="pwneu"> <img src="images/ok.gif"> </button></td>
        <!--<input type="submit" value="<?=$lang->word('ok')?>" name="pwneu">-->
    </tr>
  </table>
  <p align="center">&nbsp;</p>
</form>


<?
}
if (isset ($post['pwneu'])) {
$passwortalt=$post['passwortalt'];
$passwortneu=$post['passwort'];
$passwortneu2=$post['passwort2'];

$passwortalt = md5($passwortalt);

if ($passwortalt == $_SESSION['pass'] and $passwortneu == $passwortneu2)
{
$passwortneu = md5($passwortneu);
$eingabe = "UPDATE `".$dbpräfix."user` SET `pass` = '$passwortneu' WHERE `user` = '".$_SESSION['username']."';";
$ergebnis = $hp->mysqlquery($eingabe);
if ($ergebnis == true)
{
$_SESSION['pass']=$passwortneu;
echo $lang->word('ok_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>";
} else { echo $lang->word('error').": ".mysql_error(); }
} else { echo $lang->word('pwwrong_profil')."!<br><a href=index.php?site=profil>".$lang->word('back')."</a>"; }
}

if (isset ($post['go'])) {
$post['name']=$post['name'];
$post['nachname']=$post['nachname'];
$post['alter']=$post['alter'];
$post['geburtstag']=$post['geburtstag'];
$post['wohnort']=$post['wohnort'];

$post['cpu']=$post['cpu'];
$post['ram']=$post['ram'];
$post['hdd']=$post['hdd'];
$post['graka']=$post['graka'];

$post['clan']=$post['clan'];
$post['clanhistory']=$post['clanhistory'];
$post['clantag']=$post['clantag'];
$post['clanhomepage']=$post['clanhomepage'];
$post['tel'] = $post['tel'];
//$email="'".$post['email']."'";
//$wiifcode="'".$post['wiifcode']."'";







foreach ($post as $key=>$value) {
 $value = str_replace('<',"&lt;" ,$value);
 $value = str_replace('\'',"\"" ,$value);// , 

	$$key = "'".$value."'";
}
if ((isset($name)) and (isset($nachname)) and (isset($wohnort))) 
{

$eingabe = "UPDATE `".$dbpräfix."user` SET `name` = $name, `nachname` = $nachname, `alter` = $alter, `geburtstag` = $geburtstag, `wohnort` = $wohnort, `cpu` = $cpu,".
" `ram` = $ram, `graka` = $graka, `hdd` = $hdd, `clan` = $clan, `clantag` = $clantag  WHERE `user` = '".$_SESSION['username']."';";
//echo $eingabe;
$ergebnis2 = $hp->mysqlquery($eingabe);
echo mysql_error();
$eingabe = "UPDATE `".$dbpräfix."user` SET `clanhomepage` = $clanhomepage, `clanhistory` = $clanhistory, `tel` = $tel  WHERE `user` = '".$_SESSION['username']."';";
//echo $eingabe;
$ergebnis = $hp->mysqlquery($eingabe);
echo mysql_error();
if (($ergebnis == true) and ($ergebnis2 == true)){ 

//header("Location: ../index.php?site=profil"); 
echo $lang->word('ok_profil')."!<br><a href=index.php>".$lang->word('back')."</a>";

} else { echo $lang->word('error').": ".mysql_error(); }
} else
{
echo $lang->word('notallfields');
}

}

}


 ?> 

