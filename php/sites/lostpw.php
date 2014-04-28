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
$lbs = $hp->lbsites;

// LostPW Seite :)

if (isset($get['change']))
{

	$code = $get['change'];

	$sql = "SELECT `user`, UNIX_TIMESTAMP(`verfall`) AS `verfall` FROM `$dbprefix"."token` WHERE `token` = '$code'";
	$erg = $hp->mysqlquery($sql);
	$row = mysql_fetch_object($erg);



	if (isset($row->verfall) and ($row->verfall >= time()))
	{

		$data = array(
			"user" => $row->user,
			"code" => $code
			);

		$site = new siteTemplate($hp);
		$site->load("lostpw");
		$site->setArray($data);
		$site->display("Change");


	} else
	{
		$error->error($lang["Der angegebene Code scheint schon benutzt worden sein oder ist abgelaufen!"], "1");
	}

} else if (isset($post['change']))
{

	$code = $post['token'];

	$sql = "SELECT * FROM `$dbprefix"."token` WHERE `token` = '$code'";
	$erg = $hp->mysqlquery($sql);
	$row = mysql_fetch_object($erg);

	$pw = 'pw_'.$post['pw'];
	$pw2 = $post['pw2'];

	if ($pw != $pw2)
	{
		$error->error($lang["Die angegebenen Passwörter stimmen nicht überein!"], "1");

	} else
	{

		$sql = "UPDATE `$dbprefix"."user` SET  `pass` = '".md5($pw)."' WHERE `user` = '$row->user'";
		$erg = $hp->mysqlquery($sql);

		$sql = "DELETE FROM `$dbprefix"."token` WHERE `user` = '$row->user'";
		$erg = $hp->mysqlquery($sql);

		$site = new siteTemplate($hp);
		$site->load("info");
		$site->set("info", $lang["Passwort erfolgreich geändert!"]);
		$site->display();

	}



} else if (isset($post['lostpw']))
{
	$user = $post['username'];
	$mail = $post['email'];

	$sql = "SELECT * FROM `$dbprefix"."user` WHERE `user` = '$user'";
	$erg = $hp->mysqlquery($sql);
	$row = mysql_fetch_object($erg);

	if ($row->user == "")
	{
		$site = new siteTemplate($hp);
		$site->load("info");

		$site->set("info", $lang['error'].": ".$lang['Benutzername nicht vorhanden']."!<br><a href=?site=lostpw>".$lang['back']."</a>");
		$error->error($lang['Benutzername nicht vorhanden'].'!');
		$site->display();

	} elseif ($mail != $row->email)
	{
		$site = new siteTemplate($hp);
		$site->load("info");

		$site->set("info", $lang['error'].": ".$lang['Die Kontaktemail Adresse ist falsch']."!<br><a href=?site=lostpw>".$lang['back']."</a>");
		$error->error($lang['Die Kontaktemail Adresse ist falsch']."!");
		$site->display();

	} else
	{
		// alles OK
		$hp->lostpassword($user);
	}

} else
{
	$site = new siteTemplate($hp);
	$site->load("lostpw");
	$site->display();

}
?>
