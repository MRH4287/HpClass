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
			$info->okm("Newsmeldung erfolgreich ge�ndert!");
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

$abfrage = "SELECT n.*, u.ID AS userid FROM ".$dbprefix."news n LEFT JOIN ".$dbprefix."user u ON n.ersteller = u.user ORDER BY `ID` DESC LIMIT ".$limit;
$ergebnis = $hp->mysqlquery($abfrage);


$Content = array();

while($row = mysql_fetch_object($ergebnis))
{
	$ok = false;
	if (("$row->level" == "1") && ($right[$level]['readl1']))
	{
		$ok = true;
	} elseif (("$row->level" == "2") && ($right[$level]['readl2']))
	{
		$ok = true;
	} elseif (("$row->level" == "3") && ($right[$level]['readl3']))
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
			"level" => $row->level,
			"ersteller" => $row->ersteller,
			"userid" => $row->userid,
			"datum" => $row->datum,
			"Content" => $row->text,
			"ID" => $row->ID,
			"edit" => isset($get['delet'])
			);

		$Content[] = $data;
	}
}

$site->set("News", $Content);


$site->set("WriteNews", "  -  ".$lbsites->link("newnews","<b>".$lang['Neue Newsmeldung verfassen']."</b>"));
$site->set("StartEditNews", "  -  <a href=\"index.php?site=news&delet=true\"><b>".$lang['Newsmeldungen Bearbeiten']."</b></a>");

$site->display();
?>
