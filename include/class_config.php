<?php

class config
{
	public $hp;

	private $config = array();
	private $registed = array();
	private $cats = array();
	private $desc  = array();
	private $defaults = array();
	private $types = array();

	public function sethp($hp)
	{
		$this->hp = $hp;
		$this->load();

	}

	public function load()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();

		$abfrage = "SELECT * FROM `".$dbprefix ."config`";

		$ergebnisss = $hp->mysqlquery($abfrage);
		echo mysql_error();
		$config = array();
		while($row = mysql_fetch_object($ergebnisss))
		{

			if ($row->typ == "bool")
			{
				if ("$row->ok" == "true")
				{
					$value = true;
				} elseif ("$row->ok" == "false")
				{
					$value = false;
				}
			} else
			{
				$value = $row->ok;
			}

			$name = "$row->name";

			$this->cats[$name] = $row->kat;
			$this->desc[$name] = $row->description;
			$this->types[$name] = $row->typ;

			$config[$name] = $value;

		}
		$this->config = $config;

	}


	function getconfig()
	{
		$myconfig = array();

		foreach ($this->registed as $k => $config)
		{

			$myconfig[$config] = $this->get($config);

		}

		return $myconfig;

	}

	function getregisted()
	{

		return $this->registed;

	}

	function register($config, $desc, $cat, $type = "bool", $default = false)
	{

		if (!in_array($config, $this->registed))
		{

			$this->registed[] = $config;
			$this->cats[$config] = $cat;
			$this->desc[$config] = $desc;
			$this->defaults[$config] = $default;
			$this->types[$config] = $type;

		} else
		{

			throw new Exception("Key Allready exsists");

		}

	}

	function registerArray($configs)
	{

		foreach ($configs as $k => $data)
		{
			$this->register($data["name"], $data["desc"], $data["cat"], $data["type"], $data["default"]);

		}


	}

	function cat($config)
	{

		return $this->cats[$config];

	}

	function desc($config)
	{
		return $this->desc[$config];
	}

	function defaults($config)
	{
		return $this->defaults[$config];
	}

	function type($config)
	{
		if (isset($this->types[$config]))
		{
			return $this->types[$config];
		} else
		{
			return "NONE";
		}


	}

	function get($config)
	{
		if (isset($this->config[$config]))
		{
			return $this->config[$config];
		} elseif (isset($this->defaults[$config]))
		{
			return $this->defaults[$config];

		} else
		{
			return false;
		}

	}

	function apply($config)
	{
		$data = array();

		foreach ($this->registed as $k=>$name)
		{
			if (($this->type($name) == "bool") && in_array($name, $config))
			{
				$data[$name] = true;

			} elseif ($this->type($name) == "bool")
			{
				$data[$name] = false;

			} elseif (isset($config[$name]) && ($config[$name] != ""))
			{
				$data[$name] = $config[$name];
			}

		}

		$this->save($data);


	}



	private function save($config)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();

		if (is_array($config))
		{
			$sql = "TRUNCATE `".$dbprefix."config`;";
			$hp->mysqlquery($sql);


			foreach ($config as $name => $on)
			{

				if ($this->type($name) == "bool")
				{
					$on = ($on) ? "true" : "false";
				}

				$sql = "INSERT INTO `".$dbprefix."config` (
          `ID` ,
          `name`,
          `ok`,
          `description`,
          `typ`,
          `kat`
          )
          VALUES (
          NULL , '$name', '$on', '".$this->desc($name)."', '".$this->type($name)."', '".$this->cat($name)."'
          );";
				$hp->mysqlquery($sql);

			}



		} else
		{
			throw new Exception();
		}


	}


}
?>