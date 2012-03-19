<?php
$dbprefix = $hp->getprefix();


if (!isset($_SESSION['username']))
{
	$site = new siteTemplate($hp);
	$site->load("login");
	$template['login'] = $site->get("Login");

} else {

	$site = new siteTemplate($hp);
	$site->load("login");

	$data = array(
		'username' => $_SESSION['username'],
		'level' =>    $_SESSION['level'],
		'Links' =>    $site->get("Links")

		);


	$template['login'] = $site->getNode('List', $data);


}

?>
