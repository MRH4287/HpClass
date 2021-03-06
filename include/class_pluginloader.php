<?php

require_once "class_api.php";

class PluginLoader  extends Api
{

	protected $hp;
	public $plugins = array();
	
	private $apiCommands = array();
	private $apiContentPrefix = "api_";

	private $pluginconfig = array();

	// ------------------------------


	function sethp($hp)
	{
		$this->hp = $hp;
	}


	/*
	Startet den Plugin Loader

	*/
	function Init()
	{

		// Binde API-Funktionen ein
		$this->registerApiFunctions($this);

		// Bindet die BasisKlasse der Plugins ein
		include_once HP::$ROOT_PATH.'include/base/plugin.php';
		$this->updatePluginList();
		$this->enablePlugins();

	}

	/*

	L�d die Plugins

	*/
	function Load()
	{

		foreach ($this->plugins as $name=>$data)
		{
			if ($data["enabled"] == true)
			{
				$data["o"]->OnLoad();
			}
		}


	}

	/*
	  
	F�hrt OnSiteCreated aus
	  
	*/
	function OnSiteCreated()
	{
		
		foreach ($this->plugins as $name=>$data)
		{
			if ($data["enabled"] == true)
			{
				$data["o"]->OnSiteCreated();
			}
		}

	}


	/*

	 Liest alle Plugins aus einem bestimmtem Ordner aus

	*/
	function addFolder($dir, $extern = false)
	{
		try
		{

			if (is_dir($dir))
			{
				$handle = @opendir($dir);
				while (false !== ($file = readdir($handle)))
				{

					$n = explode(".", $file);
					if (count($n) >= 2)
					{
						$a = $n[0];
						$b = $n[count($n)-1];

						if ($b == "php")
						{

							try
							{
								include "$dir/$file";
								$myClass = new $a($this->hp, $this);


								$this->plugins[$a] = array("o" => $myClass, "enabled" => $myClass->isEnabled, "extern" => $extern, "name" => $a);

								if ($myClass->isEnabled)
								{
									$this->enablePlugin($a, true);
								}

							}
							catch (Exception $e)
							{
								$this->hp->error->error($e->getMessage());
							}


						}
					}
				}
			} else
			{
			}
		} catch (Exception $e)
		{
		}
	}


	/*

	Aktualisiert die Plugin Liste, in dem er die Daten aus dem plugins Unterordner liest
	und diese in ein Array speichert.
	Anschlie�end wird die Datenbank durchsucht und alle dort gelisteten Plugins auf enabled
	gesetzt, was dazu f�hrt, dass bei diesen Objekten, die OnEnable Funktion aufgerufen wird.

	*/
	function updatePluginList()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();

		// Alle vorhandenen Plugins laden.
		$this->addFolder(HP::$ROOT_PATH."plugins");

		//Binde alle durch Templates gegebene Plugins ein:
		$this->addFolder(HP::$ROOT_PATH."template/".$config["design"]."/plugins", true);



		// Lade die Datenbank um alle Plugins zu suchen, die Aktiviert sind

		$sql = "SELECT * FROM `$dbprefix"."plugins`";
		$erg = $hp->mysqlquery($sql);
		while ($row = mysql_fetch_object($erg))
		{

			$name = $row->name;

			if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
			{
				$this->plugins[$name]["enabled"] = true;
				$this->plugins[$name]["name"] = $name;
				$this->pluginconfig[$name] = (array)json_decode($row->config);

			} else
			{

				$this->hp->info->info("Ung�ltiger Eintrag in Plugin Tabelle entfernt");
				$sql2 = "DELETE FROM `$dbprefix"."plugins` WHERE `name` = '$name';";
				$hp->mysqlquery($sql2);


			}


		}


	}



	/*

	Aktiviert alle Plugins, bei denen das Flag enabled auf true steht

	*/
	function enablePlugins()
	{

		foreach ($this->plugins as $name=>$data)
		{
			if ($data["enabled"] == true)
			{
				$config = $this->pluginconfig[$data["name"]];
				if ($config == "")
				{
					$config = array();
				}
				$data["o"]->setConfig($config);
				if ($data["o"]->OnEnable() === false)
				{
					$this->disablePlugin($name);
				}
			}
		}

	}

	/*!

	  Speichert die Config Daten

	*/
	public function Save()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();


		foreach ($this->plugins as $name=>$data)
		{
			$conf = json_encode($data["o"]->getConfig());
			if (isset($this->pluginconfig[$name]) && ($conf != (json_encode($this->pluginconfig[$name]))))
			{
				$sql = "UPDATE `$dbprefix"."plugins` SET `config` = '".mysql_real_escape_string($conf)."' WHERE `name` = '$name';";
				$erg = $hp->mysqlquery($sql);
			}
		}


	}


	/*

	  Liefert die Objektinstanz des Plugins mit dem angegebenen Namen zur�ck

	*/
	function getPlugin($name)
	{
		if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
		{
			return $this->plugins[$name]["o"];
		} else
		{
			return null;
		}

	}


	/*

	  Liefert zur�ck, on ein Plugin mit einem angegebenen Namen aktiviert ist

	*/
	function isEnabled($name)
	{
		if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
		{
			return $this->plugins[$name]["enabled"];
		} else
		{
			return null;
		}

	}


	/*

	 F�gt ein Plugin zu der Liste der erlaubten Plugins hinzu

	*/
	function enablePlugin($name, $force = false)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;

		if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
		{
			$plugin = $this->plugins[$name];

			if (!$plugin["o"]->lock || $force)
			{
				// Eintragen in die Datenbank;

				$sql = "SELECT * FROM `$dbprefix"."plugins` WHERE `name` = '$name';";
				$erg = $hp->mysqlquery($sql);
				if (mysql_num_rows($erg) == 0)
				{

					$sql = "REPLACE INTO `$dbprefix"."plugins` (`name`) VALUES ('$name');";
					$erg = $hp->mysqlquery($sql);

				}

				return true;

			} else
			{
				return -1;
			}

		} else
		{
			return false;
		}

	}


	/*

	 Entfernt ein Plugin von der Liste der erlaubten Plugins

	*/
	function disablePlugin($name, $force = false)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;


		if (isset($this->plugins[$name]) and is_array($this->plugins[$name]))
		{
			$plugin = $this->plugins[$name];

			if (!$plugin["o"]->lock || $force)
			{
				// Eintragen in die Datenbank;

				$sql = "DELETE FROM `$dbprefix"."plugins` WHERE `name` = '$name';";
				$erg = $hp->mysqlquery($sql);

				return true;

			} else
			{
				return -1;
			}

		} else
		{
			return false;
		}

	}


	/*

	  �berpr�ft ob ein Plugin Zus�tzliche Informationen (Autor, Homepage, Notizen) enth�lt

	*/
	function containsInfo($name)
	{
		$plugin = $this->getPlugin($name);
		if ($plugin == null)
		{
			return null;
		} else
		{

			return (($plugin->autor != "") || ($plugin->homepage != "") || ($plugin->notes != ""));

		}

	}


	/*!

	  Registriert API Funktionen im Pluginloader

	*/
	public function registerApiFunctions($object)
	{
		$methods = get_class_methods($object);
		
		foreach ($methods as $m)
		{
			$p = $this->apiContentPrefix;
			if (preg_match("/^{$p}[a-z]/", $m))
			{
				$m2 = preg_replace("/^{$p}([a-z])/e", "strtolower('$1')", $m);

				$data = array();
				$data["name"] = $m2;
				$data["function"] = $m;
				$data["object"] = $object;
				
				$this->apiCommands[$m2] = $data;	
			}
		}
	}


	/*!

	  API Funktionen ausf�hren

	*/
	protected function executeCommand($event, $command, $arguments)
	{
		$hp = $this->hp;

		if (isset($this->apiCommands[$command]) && (is_array($this->apiCommands[$command])))
		{

			$o = $this->apiCommands[$command]["object"];
			$f = $this->apiCommands[$command]["function"];

			return json_encode($o->$f($event, $arguments));

		} else
		{
			return json_encode(array("error" => "Unknown Function"));
		}


	}


	// --------------------------  API - Funktionen ----------------------------------------


	function api_ping($event, $arguments)
	{
		return "pong";
	}


	function api_echo($event, $arguments)
	{
		return implode(", ", $arguments);
	}




}
?>