<?php
class widgets
{
	var $hp;
	var $placed = array();
	var $template = array();
	var $tempconfig = array();
	
	// is Set by the dragdrop Page over a Template Function
	var $enableWidgetPlacing = false;

	// Leeres Array für die Widgets:
	var $widgets = array();

	public function __construct()
	{
		AjaxFunctions::extend($this);
		template::extend($this);

	}


	function sethp($hp)
	{
		$this->hp = $hp;
	}

	function replace()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$temp = $hp->template;

		$this->template = array();
		$this->placed = array();
		$this->widgets = array();

		// Fügt Widgets aus den Templates hinzu:
		$this->incwidgetfiles();

		// Datenbank Abfrage, ob bereits ein Widget verschoben wurde:
		$sql = "SELECT * FROM `$dbprefix"."widget`";
		$erg = $hp->mysqlquery($sql);
		while ($row = mysql_fetch_object($erg))
		{
			if (isset($this->widgets[$row->source]))
			{

				$value = $this->widgets[$row->source];
				$this->template[$row->ID] = array(
					"source" => $row->source,
					"content" => "<div id='widget_".$row->ID."'>$value</div>"
				);
				
				$this->placed[] = $row->source;
				$this->placed[] = $row->ID;
			}

		}

	} 


	function getParent($widget)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;

		$sql = "SELECT * FROM `$dbprefix"."widget` WHERE `source` = '$widget';";
		$erg = $hp->mysqlquery($sql);
		$row = mysql_fetch_object($erg);

		return $row->ID;

	}


	function isPlaced($widget)
	{
		return in_array($widget, $this->placed);
	}

	function getwidgets($placed = false, $config = true)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$temp = $hp->template;
		$lbs = $hp->lbsites;
		$widgets = array();


		foreach ($this->widgets as $key=>$value) {


			if (!in_array($key, $this->placed) or $placed)
			{
				$widgets[$key] = $temp->replace($value, "");

				if (array_key_exists($key, $this->tempconfig) and $config)
				{
					$widgets[$key] .= "<center>".$lbs->link($this->tempconfig[$key], "<img src=\"images/edit.gif\">")."</center>";

				}


			}
		}


		return $widgets;
	}


	function addwidget($name, $value)
	{
		$this->widgets[$name] = $value;
	}


	function addplaceholder($name)
	{

		if (!in_array($name, $this->placeholder))
		{
			$this->placeholder[] = $name;
		}


	}



	function incwidgetfiles()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->firephp;
		$config = $hp->getconfig();

		$design = $config['design'];

		if (is_dir(HP::$ROOT_PATH . "template/".$design."/widgets/"))
		{


			$handle = @opendir(HP::$ROOT_PATH . "template/".$design."/widgets/");
			while ($file = @readdir($handle)) {



				$n= @explode(".",$file);
				$art = @strtolower($n[1]);


				if ($art == "php")
				{
					$widget = array();
					$placeholder = array();
					if (file_exists(HP::$ROOT_PATH . "template/".$design."/widgets/$file"))
						include (HP::$ROOT_PATH . "template/".$design."/widgets/$file");

					foreach ($widget as $key=>$value) {
						
						if (($key != "") and ($value != ""))
						{
							$this->addwidget($key, $value);
						}
						
					}

					if (isset($tempconfig) && is_array($tempconfig))
					{
						$this->tempconfig = array_merge($this->tempconfig, $tempconfig);
						//  print_r($this->tempconfig);
					}


				}
			}


		}
	}


	function getConfig($widget)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();


		$sql = "SELECT * FROM `$dbprefix"."widgetconfig` WHERE `widget` = '$widget';";
		$erg = $hp->mysqlquery($sql);
		$row = mysql_fetch_object($erg);

		$config = unserialize($row->config);

		if (!is_array($config))
		{
			$config = array();
		}

		return $config;
	}

	function saveConfig($widget, $config)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();

		$text = serialize($config);

		$sql = "REPLACE INTO `$dbprefix"."widgetconfig` (`widget`, `config`) VALUES ('$widget', '$text');";
		$erg = $hp->mysqlquery($sql);

	}

	
	// --------------- Axax Functions -----------------
	
	public function ajax_widgetsAction($args)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		
		
		$superadmin = ( isset($_SESSION['username']) && in_array($_SESSION['username'], $hp->getsuperadmin()));
		
		if (!$superadmin)
		{
			header("HTTP/1.0 403 Forbidden");
			return "Forbidden";
		}
		else
		{
			$event = $args['event'];
			$moved = $args['moved'];
			$parent = $args['parent'];

			if ($event == "delete")
			{
							
				$sql = "DELETE FROM `$dbprefix"."widget` WHERE `source` = '".$moved."';";
				$hp->mysqlquery($sql);
			}
			elseif ($event == "move")
			{
				$sql = "UPDATE `$dbprefix"."widget` SET `ID` = '".$parent."' WHERE `source` = '".$moved."';";
				$hp->mysqlquery($sql);
			}
			elseif ($event == "create")
			{
				$sql = "INSERT INTO `$dbprefix"."widget` (`ID`, `source`) VALUES ('".$parent."', '".$moved."');";
				$hp->mysqlquery($sql);
			}
			else
			{
				return "Event-Type not found";
			}
	
		}
		
		// Reload Widgets and send Data to Client:
		$this->replace();
		
		foreach ($this->template as $key => $value)
		{
			$this->template[$key]["content"] = $hp->template->replace($this->template[$key]["content"], "");
		}
		
		return array(
			"template" => $this->template,
			"notPlaced" => $this->getwidgets(false, true)
		);
		
	}
	
	
	
	// ------------------ Template ---------------------
	
	
	/*
	 * 	Sets a Placeholder for Widgets
	 * 
	 *  Arguments:
	 *  0 - Name
	 * 
	 */
	public function temp_widgetSetPlaceholder($args)
	{
		$hp = $this->hp;
		
		if (count($args) >= 1)
		{
			$name = $args[0];
			
			if (isset($this->template[$name]))
			{
				if ($this->enableWidgetPlacing)
				{
					$temp = new siteTemplate($hp);
					$temp->load("widgets");
					$temp->set("Content", $this->template[$name]["content"]);
					$temp->set("name", $this->template[$name]["source"]);
					$temp->set("placeholder", $name);
				
					return $temp->get("Element");
				}
				else
				{				
					return $this->template[$name]["content"];
				}
			} 
			elseif ($this->enableWidgetPlacing)
			{
				$temp = new siteTemplate($hp);
				$temp->load("widgets");
				
				$temp->set("ID", $name);
				
				return $temp->get("Placeholder");
			}
			else
			{
				return "";
			}
		} 
		else
		{
			return "[widgetSetPlaceholder: Not enough Arguments]";
		}
				
	}
	
	/*
	 * Enables Placing of widgets
	 */
	public function temp_enableWidgetPlacing($args)
	{
		$this->enableWidgetPlacing = true;
		
		return "";
	}

	/*
	 * Returns the not Placed Widgets
	 */
	public function temp_getWidgets($args)
	{
		$data = array();
		
		foreach ($this->getwidgets() as $name => $value)
		{
			$data[] = array(
				"name" => $name,
				"value" => $value
			);
		}
		
		return $data;
	}
	
}
?>