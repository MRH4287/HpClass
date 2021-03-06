<?php

class right
{

	public $hp;

	private $recht = array();
	private $registed = array();
	private $cats = array();
	private $desc  = array();
	private $levels = array();


	private $permissionCache = array();

	public function sethp($hp)
	{

		$this->hp = $hp;
		$this->load();

	}


	function load()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();

		// Rechte
		$abfrage = "SELECT * FROM `".$dbprefix."right` Order By `level` ASC";
		//$ergebnis = SQLexec($abfrage, "index");
		$ergebnisss = $hp->mysqlquery($abfrage);
		$right = array();
		while($row = mysql_fetch_object($ergebnisss))
		{
			$rlevel=$row->level;

			if ("$row->ok" == "true")
			{
				$value = true;
			} else
			{
				$value = false;
			}
			$rright = $row->right;


			$this->cats[$rright] = $row->cat;
			$this->desc[$rright] = $row->description;
			$right[$rlevel][$rright] = $value;
		}

		$this->recht = $right;


	}

	function is($right, $level = null)
	{
		if (($level != "0") && ($level == null))
		{
			$level = $_SESSION['level'];

		}


		return (((isset($this->recht[$level][$right]))) && ($this->recht[$level][$right]) && (in_array($right, $this->registed)));


	}


	/*!

	  Wegen R�ckwertskompatibilit�t

	*/
	function getright()
	{
		$myright = array();

		foreach ($this->levels as $level => $d)
		{

			foreach ($this->registed as $k => $right)
			{

				$myright[$level][$right] = (isset($this->recht[$level][$right])) ? $this->recht[$level][$right] : false;

			}



		}

		return $myright;

	}

	function getregisted()
	{

		return $this->registed;

	}

	function register($right, $desc, $cat)
	{

		if (!in_array($right, $this->registed))
		{

			$this->registed[] = $right;
			$this->cats[$right] = $cat;
			$this->desc[$right] = $desc;

		} else
		{

			throw new Exception("Key Allready exsists");

		}



	}


	function registerArray($rights)
	{

		foreach ($rights as $k => $data)
		{
			$this->register($data["name"], $data["desc"], $data["cat"]);

		}


	}

	function registerLevel($level, $parent=null)
	{

		if (is_array($level))
		{
			foreach ($level as $k => $data)
			{
				$this->registerLevel($data[0], $data[1]);
			}
		} else
		{
			$this->levels[$level] = $parent;
		}

	}


	function getlevels()
	{
		$levels = array();
		foreach ($this->levels as $key => $data)
		{
			$levels[] = $key;
		}

		return $levels;
	}

	function getLevelNames()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();


		$ranks = array();

		$sql = "SELECT * FROM `$dbprefix"."ranks`";
		$erg = $hp->mysqlquery($sql);
		while ($row = mysql_fetch_object($erg))
		{
			$ranks[$row->level] = $row->name;
		}

		$levels = array();
		foreach ($this->levels as $key => $data)
		{
			$name = isset($ranks[$key]) ? $ranks[$key] : $key;
			$levels[$key] = $name;
		}

		return $levels;


	}

	function cat($right)
	{

		return $this->cats[$right];

	}

	function desc($right)
	{
		return $this->desc[$right];
	}


	function isAllowed($needed, $level = null)
	{
		if ($level == null)
		{
			$level = $_SESSION["level"];
		}

		if (isset($this->permissionCache[$level]) && isset($this->permissionCache[$level][$needed]))
		{
			return $this->permissionCache[$level][$needed];
		}
		$allowed = $this->rec_isAllowed($level, $needed);

		if (!isset($this->permissionCache[$level]))
		{
			$this->permissionCache[$level] = array();
		}

		$this->permissionCache[$level][$needed] = $allowed;


		return $allowed;


	}


	private function rec_isAllowed($level, $needed, $visited = null)
	{

		if ($visited == null)
		{
			$visited = array();
		}
		$parent = $this->levels[$level];
		$siblings = array();
		foreach ($this->levels as $l => $p)
		{
			if ($p == $parent)
			{
				$siblings[] = $l;
			}

		}


		if (($level == $needed) || (in_array($needed, $siblings)))
		{
			return true;

		} elseif (($parent != null) && (!in_array($parent,$visited)))
		{

			return $this->rec_isAllowed($parent, $needed, $visited);

		} else
		{
			return false;
		}

	}




	function save($rights)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();

		if (is_array($rights))
		{
			$sql = "TRUNCATE `".$dbprefix."right`;";
			$hp->mysqlquery($sql);

			foreach ($rights as $aktlevel => $right)
			{
				foreach ($right as $name => $on)
				{

					$on = ($on) ? "true" : "false";

					$sql = "INSERT INTO `".$dbprefix."right` (
            `ID` ,
            `level` ,
            `right`,
            `ok`,
            `description`,
            `cat`
            )
            VALUES (
            NULL , '$aktlevel', '$name', '$on', '".$this->desc($name)."', '".$this->cat($name)."'
            );";
					$hp->mysqlquery($sql);

				}

			}

		} else
		{
			throw new Exception();
		}


	}



}
?>