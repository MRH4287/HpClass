<?php
class Template extends SiteTemplate
{

	var $template = array();
	var $path;



	function __construct($hp)
	{
		parent::__construct($hp);
	}


	function seterror($error)
	{
		$this->error=$error;
	}

	function gettemp($part)
	{
		return $this->template[$part];
	}

	function settemplate($temp)
	{
		$this->setArray($temp);
	}


	// The second parameter is not used in this overwrite Function
	function load($path, $direct = null)
	{
		$this->path = $path;

		if (!file_exists(HP::$ROOT_PATH . "template/$path.html"))
		{

			$this->template=array();
			$this->hp->error->error("Template $path not found!", "2");
			if (file_exists(HP::$ROOT_PATH . "template/default.html"))
			{
				$path = "default";
			} else
			{
				$this->error->error("Standard Template wurde nicht gefunden!","3");
			}
		}

		parent::load(HP::$ROOT_PATH . "template/$path.html", true);

		//$temp = file_get_contents("template/$path.html");




		$temp = $this->loadtemplatefile($path);
		if (is_array($temp))
		{
			$this->setArray($temp);
		}

		$this->addVote();

		//$this->data = $this->spezialsigs($data);
		
		if (isset($this->hp->subpages))
		{
			$this->hp->subpages->loadTemplateFile($path);
		}
	}

	public function addScriptToHeader($script)
	{
		$hp = $this->hp;
		$temp = new siteTemplate($hp);
		$temp->load("utilities");
		$temp->set("Content", $script);
		
		$this->append("head", $temp->get("AutorunScript"));
	}
	

	function loadtemplatefile($path)
	{

		if (file_exists(HP::$ROOT_PATH . "template/$path/template.php"))
		{

			include HP::$ROOT_PATH . "template/$path/template.php";
			return $template;
		}

		return array();

	}

	function addVote()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$right = $hp->getright();
		$lbs = $hp->lbsites;

		$level = $_SESSION["level"];



		$sql = "SELECT `ID`, `userid`, `antworten`, `name`, `ergebnisse`, `voted`, UNIX_TIMESTAMP(`timestamp`) AS `timestamp`, UNIX_TIMESTAMP(`upto`) AS `upto` FROM `$dbprefix"."vote`";
		$erg = $hp->mysqlquery($sql);


		while ($row = mysql_fetch_object($erg))
		{

			$site = new siteTemplate($hp);
			$site->load("vote");

			$ergebniss = explode("<!--!>", $row->ergebnisse);
			$voted = count($ergebniss);
			if ($ergebniss[0] == "")
			{
				$voted--;
			}
			$whov = explode("<!--!>", $row->voted);

			$data = array(
				"name" => $row->name,
				"ID" => $row->ID
				);

			$site->setArray($data);

			$content = "";
			if ($row->upto > time())
			{

				if (isset($_SESSION['ID']) && !in_array($_SESSION['ID'], $whov))
				{

					$answers = explode("<!--!>", $row->antworten);

					$votes = "";
					foreach ($answers as $key=>$value)
					{

						$data2 = array(
							"ID" => $data["ID"],
							"key" => $key,
							"value" => $value
							);

						$votes .= $site->getNode("Vote-Element", $data2);

					}

					$data2 = array_merge(array(), $data);
					$data2["votes"] = $votes;

					$content = $site->getNode("Vote-List", $data2);

				} else
				{
					$content = $site->getNode("Vote-Voted", $data);
				}

			} else
			{
				$content = $site->getNode("Vote-Out", $data);
			}

			$site->set("content", $content);
			$site->set("votes", $voted);

			$this->set('vote-'.$row->ID, $site->get("Vote"));


		}

	}


}
?>
