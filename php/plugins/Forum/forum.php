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
$right = $hp->right;


// try to load something:

$site = new pluginTemplate($this, 'forum');
$node = 'Main';


if( isset($get['forums']))
{
	$site->load('forums');

} elseif (isset($get['fid']))
{

	$site->load('threads');
	$site->set('fid', intval($get['fid']));

} elseif (isset($get['tid']))
{
	$site->load('threadView');
	$site->set('tid', intval($get['tid']));

} elseif (isset($get['editForum']) && ($right->is('manage_forums')))
{

	$id = intval($get['editForum']);

	$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `ID` = $id;";
	$erg = $hp->mysqlquery($sql);

	if (mysql_num_rows($erg) == 0)
	{
		$site = new siteTemplate($hp);
		$site->load('info');
		$site->set('info', 'Es gibt kein Forum mit dieser ID!<br/><a href="?site=forum&newForum">Zurück</a>');

	} else
	{

		$row = mysql_fetch_object($erg);

		$site->load('forumEdit');

		$ranks = $hp->right->getLevelNames();

		$levels = array();
		foreach($ranks as $level => $name)
		{
			$levels[] = array(
				'level' => $level,
				'name' => $name
				);
		}

		$data = array(
			'levels' => $levels,
			'edit' => true,
			'ID' => $row->ID,
			'titel' => $row->titel,
			'description' => $row->description,
			'level' => $row->level,
			'visible' => ($row->visible == 1)

			);

		$site->setArray($data);

	}

} elseif (isset($get['newForum']) && ($right->is('manage_forums')))
{

	$site->load('forumEdit');

	$ranks = $hp->right->getLevelNames();

	$levels = array();
	foreach($ranks as $level => $name)
	{
		$levels[] = array(
			'level' => $level,
			'name' => $name
			);
	}

	$data = array(
		'levels' => $levels,
		'edit' => false,
		'ID' => -1,
		'titel' => '',
		'description' => '',
		'level' => 0,
		'visible' => false

		);

	$site->setArray($data);


}  elseif (isset($post['newForum']) && ($right->is('manage_forums')))
{
	$titel = $post['titel'];
	$level = $post['level'];
	$visible = isset($post['visible']) ? 1 : 0;
	$description = $post['description'];

	$passwort = '';
	$typ = 0;

	$uid = $_SESSION['ID'];

	$sql = "SELECT * FROM `$dbprefix"."forums` WHERE `titel` = '$titel';";
	$erg = $hp->mysqlquery($sql);
	if (mysql_num_rows($erg) > 0)
	{
		$site = new siteTemplate($hp);
		$site->load('info');
		$site->set('info', 'Es existiert bereits ein Forum mit diesem Titel!<br/><a href="?site=forum&newForum">Zurück</a>');

	} else
	{

		$sql = "INSERT INTO `$dbprefix"."forums` (`titel`, `userid`, `level`, `passwort`, `visible`, `description`, `type`, `timestamp`)
              VALUES('$titel', '$uid', '$level', '$passwort', $visible, '$description', $typ, NOW());";

		$erg = $hp->mysqlquery($sql);

		$hp->info->okn("Forum erfolgreich erstellt");

		$site->load('index');

	}

} else
{
	$site->load('index');
}
$site->display($node);

?>