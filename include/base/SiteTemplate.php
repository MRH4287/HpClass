<?php

/*!

  Das Template einer Unterseite

*/
class siteTemplate
{
	protected $hp;

	public $name;
	public $autor;


	protected $blocks = array();
	protected $data = array();
	protected $vars = array();


	private $aktArray = null;

	private $DEFAULT_NODE = "Main";

	private $neededRight = null;
	protected $searchpath = "";
	protected $searchpathT = "";

	// For Debug
	protected $path = "";

	// if this value is true, then a error Message will be displayed to the user
	protected $error = false;

	public static $functions = array();
	
	public function __construct($hp, $copy = null)
	{
		$this->hp = $hp;
		$this->searchpath = HP::$ROOT_PATH."template/sites";
		$this->searchpathT = HP::$ROOT_PATH."template/#!Design#/sites";

		if ($copy != null)
		{
			if (is_a($copy, "siteTemplate"))
			{
				foreach($copy->data as $k=>$v)
				{
					$this->data[$k] = $v;
				}
				
				foreach($copy->vars as $k=>$v)
				{
					$this->vars[$k] = $v;
				}
			} else
			{
				$hp->error->error("siteTemplate: supplied Object is no siteTemplate");
			}
		} else
		{
			self::extend($this);
		}
	}

	/*!

	  Läd und parst die Template Daten
	  @param path Pfad zu der lesenden Datei

	*/
	public function load($name, $direct = false)
	{

		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$info = $hp->info;
		$error = $hp->error;
		$fp = $hp->fp;
		$config = $hp->getconfig();

		if (!$direct)
		{
			$design = $config["design"];

			$searchpath = $this->searchpath;
			$searchpathT = str_replace("#!Design#", $design, $this->searchpathT);

			$path = "";
			if (is_dir($searchpathT) && is_file("$searchpathT/$name.html"))
			{
				$path = "$searchpathT/$name.html";
			} else
			{
				$path = "$searchpath/$name.html";
			}
		} else
		{
			$path = $name;
		}

		$this->path = $path;

		if (!file_exists($path))
		{
			$error->error("Template not found! ($path)");
			$this->error = true;
			return;

		}
		// Laden der Datei
		$input =  file_get_contents($path);

		//Pasen der Datei:

		$lines = preg_split("/[\n|\r]/", $input);
		foreach ($lines as $lineNr=>$line)
		{

			if (preg_match("/\#\!\!NAME=(.*)/", $line, $m))
			{
				$this->name = $m[1];
			}

			if (preg_match("/\#\!\!AUTOR=(.*)/", $line, $m))
			{
				$this->autor = $m[1];
			}

		}

		if (empty($this->name))
		{
			if (is_object($this->hp->info))
			{
				$this->hp->info->info("siteTemplate: No name defined! ($name)");
			} else
			{
				echo "<b>Warning:</b> siteTemplate-No name defined! ($name) <br />";
			}
		}

		$data = preg_split("/(\[\!=([^!]*)!])/", $input);

		foreach ($data as $key=>$value)
		{
			$blockname = "None";
			$lines = preg_split("/[\n\r]/", $value);
			$content = "";
			foreach ($lines as $lineNr=>$line)
			{
				if (preg_match("/\[\!\/([^!]*)\!]/", $line, $m))
				{
					$blockname = $m[1];
				} elseif (($line != "") && ($blockname == "None"))
				{
					$content .= $line."\n";
				}

			}
			$this->blocks[$blockname] = $content;
		}

	}

	private function getPlaceholder($content, $key = "!", $trust = false)
	{
		$result = array();

		// Herausfiltern der Platzhalter:
		if (preg_match_all("/#".((!$trust) ? "\\" : "").$key."[^\#]*#/", $content, $m2))
		{
			foreach ($m2 as $k=>$data)
			{
				foreach ($data as $key2 => $placeholder)
				{
					$result[] = str_replace("#", "", str_replace("#$key", "", $placeholder));

				}
			}

		}

		return $result;
	}

	public function replace($data)
	{
		// Ersetzt die Variablen
		// Diese Zeile muss oben stehen, da Funktionen auf Variablen zugreifen können!
		$data = $this->replaceVars($data);
		// Ersetzt die Funktionen
		// Diese Zeile muss oben stehen, da über Funktionen Variablen gesetzt werden können!
		$data = $this->replaceFunctions($data);
		// Ersetze Inline Platzhalter:
		$data = $this->replaceDefault($data);
		// Die Kommentare in den Templates werden ersetzt
		$data = $this->replaceComment($data);
		// Ersetze die Sprachblocks
		$data = $this->replaceLangBlocks($data);
		// Ersetze Bedingte Ausgaben
		$data = $this->replaceCondit($data);
		// Ersetzte die Gleichheitsprüfung
		$data = $this->replaceEquals($data);
		// Ersetzte die LbSites
		$data = $this->replaceLbSite($data);
		//Ersetzte die Loops
		$data = $this->replaceLoop($data);


		return $data;
	}

	private function replaceDefault($data)
	{
		foreach($this->data as $key=>$value)
		{
			if (!is_array($value) && !is_object($value))
			{
				$data = str_replace("#!$key#", $value, $data);
			}
		}
		foreach($this->vars as $key=>$value)
		{
			if (!is_array($value) && !is_object($value))
			{
				$data = str_replace("#!$key#", $value, $data);
			}
		}

		return $data;
	}


	private function replaceComment($data)
	{
		$langData = $this->getPlaceholder($data, "/");
		foreach ($langData as $k=> $word)
		{
			$data = str_replace("#/$word#", "", $data);
		}

		return $data;

	}

	private function replaceVars($data)
	{
		$PData = $this->getPlaceholder($data, "V\\:", true);
		foreach ($PData as $k=> $word)
		{
			$word = str_replace("V:", "", $word);

			$split = explode(" : ", $word);
			$content = "";

			if (count($split) == 2)
			{
				$this->vars[$split[0]] =  $this->replaceExtendedInput($split[1]);
			} else
			{
				$content = "[Var?]";
			}

			$data = str_replace("#V:$word#", $content, $data);
		}

		return $data;

	}

	private function replaceLangBlocks($data)
	{
		$hp = $this->hp;
		$lang = $hp->getlangclass();


		$langData = $this->getPlaceholder($data, "%");

		foreach ($langData as $k=> $word)
		{
			$data = str_replace("#%$word#", $lang->word($word), $data);
		}

		return $data;
	}

	private function replaceFunctions($data)
	{
		$hp = $this->hp;
		$tempData = $this->getPlaceholder($data, "@");

		foreach ($tempData as $k=> $word)
		{

			// Check for newer Syntax
			if (preg_match('/(?<![: @a-zA-Z0-9])([^\(^\)^:]*)\((.*)\)/', $word, $m))
			{
				$split = explode(", ", $m[2]);
				$sp = array($m[1]);
				foreach($split as $k=>$v)
				{
					$sp[] = $v;
				}
				$split = $sp;

			} else
			{

				$split = explode(" : ", $word);
			}

			$out = "";
			if (isset(self::$functions[$split[0]]))
			{
				$sys = self::$functions[$split[0]];

				$obj = $sys["obj"];
				$func = $sys["func"];

				$args = array();

				for ($i=1; $i < count($split); $i++)
				{
					$el = $split[$i];

					$content = $this->replaceExtendedInput($el);

					$args[] = $content;

				}


				$out = $obj->$func($args, $this);


			} else
			{
				$out = "[@-Func?]";
			}

			$data = str_replace("#@$word#", $out, $data);
		}

		return $data;
	}

	private function replaceLbSite($data)
	{
		$hp = $this->hp;


		$Data = $this->getPlaceholder($data, "+");

		foreach ($Data as $k => $word)
		{
			$values = explode(" = ", $word);
			$content = "";
			$content = $this->replaceExtendedInput($values[1]);

			$vars = "";
			if (count($values) > 2)
			{
				$vars = $this->replaceExtendedInput($values[2]);
			}

			$type = "lbOn";
			if (count($values) > 3)
			{
				$type = $this->replaceExtendedInput($values[3]);
			}
			$data = str_replace("#+$word#", $hp->lbsites->link($values[0], $content, $vars, $type), $data);

		}
		return $data;
	}

	private function replaceEquals($data)
	{
		$hp = $this->hp;
		$right = $hp->getright();

		$Data = $this->getPlaceholder($data, "=");

		foreach ($Data as $k => $word)
		{
			// name == "test" : "a #%test#" : b
			// name == bla : abc : %lol

			$values = explode(" : ", $word);
			$con = explode(" == ", $values[0]);

			$compareA = "A";
			$compareB = "B";

			if (count($values) != 3)
			{
				$data = str_replace("#=$word#", "[Args?]", $data);
				return $data;
			}
			
			// A
			$compareA = $this->replaceExtendedInput($con[0]);

			// B
			$compareB = $this->replaceExtendedInput($con[1]);

			// Values:
			$iftrue = $this->replaceExtendedInput($values[1], "[T]");
			$iffalse = $this->replaceExtendedInput($values[2], "[F]");

			$data = str_replace("#=$word#", ($compareA == $compareB)? $iftrue : $iffalse, $data);
		}
		return $data;
	}

	private function replaceCondit($data)
	{
		$hp = $this->hp;
		$right = $hp->getright();
		$config = $hp->getconfig();

		if (isset($_SESSION['level']))
		{
			$level = $_SESSION['level'];
		} else
		{
			$level = 0;
		}
		$conditData = $this->getPlaceholder($data, "?");

		foreach ($conditData as $k => $word)
		{
			$con = explode(" : ", $word);
			$rightN = $con[0];

			$iftrue = $this->replaceExtendedInput($con[1], "[T]");
			$iffalse = "";

			if (isset($con[2]))
			{
				$iffalse = $this->replaceExtendedInput($con[2], "[F]");
			}

			if (preg_match("/\:(.*)/", $rightN, $m))
			{
				$name = $m[1];
				$in = $this->replaceExtendedInput($name, "false");
				$output = ((($in === "true") || ($in === true)) ? $iftrue : $iffalse);
				
			} elseif (preg_match("/\=(.*)/", $rightN, $m))
			{
				$name = $m[1];
				if (isset($config[$name]))
				{
					$output = (($config[$name]) ? $iftrue : $iffalse);
				} else
				{
					$output = "[config?]";
				}
			} elseif (isset($right[$level][$rightN]))
			{
				$output = ($right[$level][$rightN] ? $iftrue : $iffalse);

			} elseif (($rightN == "superadmin"))
			{
				$output = (isset($_SESSION['username']) && in_array($_SESSION['username'], $hp->getsuperadmin())) ? $iftrue : $iffalse;
			} elseif (($rightN == "login"))
			{
				$output = (isset($_SESSION['username'])) ? $iftrue : $iffalse;

			} else
			{
				$output = "[right?]";
			}

			$data = str_replace("#?$word#", $output, $data);
		}

		return $data;
	}

	private function replaceLoop($data)
	{
		$hp = $this->hp;

		$PData = $this->getPlaceholder($data, "L\\:", true);

		foreach ($PData as $k=> $word)
		{
			$word = str_replace("L:", "", $word);
			$content = "";

			$split = explode(" : ", $word);

			if (count($split) >= 2)
			{
				$array = $this->replaceExtendedInput($split[0]);
				if (is_array($array))
				{
					$text = "";
					foreach ($array as $key => $value)
					{
						$this->aktArray = $value;
						$myBase =  $this->replaceExtendedInput($split[1], "");
						$text .= $this->replaceLoopContent($myBase, $key, $value);
						$this->aktArray = null;
					}
					$content = $text;

				} else
				{
					$content = "[Array?]";
				}
			} else
			{
				$content = "[Output?]";
			}
			$data = str_replace("#L:$word#", $content, $data);
		}
		return $data;
	}

	private function replaceLoopContent($data, $key, $value)
	{
		$hp = $this->hp;
		if (is_array($value))
		{
			$this->aktArray = $value;

			$PData = $this->getPlaceholder($data, "l:", true);
			foreach ($PData as $k=> $word)
			{
				$word = str_replace("l:", "", $word);
				$content = "";

				$split = explode(".", $word);

				if ((count($split) == 1) && ($split[0] !== "K"))
				{
					if (isset($value[$split[0]]))
					{
						$content = $value[$split[0]];

					} else
					{
						$content = "[Unknown Array Index!]";
					}
				}
				elseif ((count($split) == 2) && ($split[0] === "V"))
				{
					if (isset($value[$split[1]]))
					{
						$content = $value[$split[1]];
					} else
					{
						$content = "[Unknown Array Index!]";
					}
				}
				elseif ((count($split) == 1) && ($split[0] === "K"))
				{
					$content = $key;
				}

				$data = str_replace("#l:$word#", $content, $data);
			}
		} else
		{
			$data = "[Second Array?]";
		}
		$this->aktArray = null;

		return $data;
	}

	private function replaceExtendedInput($input, $fallback = "")
	{
		$hp = $this->hp;

		$content = "";

		if (preg_match("/@([^\"]*)\((.*)\)/", $input, $m))
		{
			if (isset(self::$functions[$m[1]]))
			{
				$sys = self::$functions[$m[1]];

				$obj = $sys["obj"];
				$func = $sys["func"];

				$args = array();

				$split = explode(", ", $m[2]);

				foreach ($split as $i => $el)
				{
					$content = $this->replaceExtendedInput($el);
					$args[] = $content;
				}
				$content = $obj->$func($args, $this);
			} else
			{
				$content = "[#-Func?]";
			}

		} elseif (preg_match("/\"(.*)\"/", $input, $m))
		{
			$content = $this->replaceLangBlocks($this->replaceDefault($m[1]));

		}
		elseif (preg_match("/%(.*)/", $input, $m))
		{

			$content = $hp->getlangclass()->word($m[1]);
		}
		elseif (preg_match("/!!(.*)/", $input, $m))
		{
			$name = $m[1];

			if (isset($this->blocks[$name]))
			{
				$content = $this->blocks[$name];
			} else
			{
				$content = "[Block?]";
			}
		}
		elseif (preg_match("/!(.*)/", $input, $m))
		{
			$name = $m[1];

			if (isset($this->blocks[$name]))
			{
				$content = $this->replace($this->blocks[$name]);

			} else
			{
				$content = "[Block?]";
			}
		}
		elseif (preg_match("/l\:(.*)/", $input, $m))
		{
			$input = $m[1];

			if ($this->aktArray != null)
			{

				if (isset($this->aktArray[$input]))
				{
					$content = $this->aktArray[$input];

				} else
				{
					$content = "[Unknown Array Index!]";
				}

			} else
			{
				$content = "[l: Array?]";
			}

		} elseif (isset($this->data[$input]))
		{
			$content = $this->data[$input];

		} elseif (isset($this->vars[$input]))
		{
			$content = $this->vars[$input];
		}
		else
		{
			$content = $fallback;
		}

		return $content;

	}


	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function append($key, $value)
	{
		if (!isset($this->data[$key]))
		{
			$this->set($key, $value);
		} else
		{
			$this->data[$key] = $this->data[$key] . $value;
		}
	}

	public function setArray($data)
	{
		foreach ($data as $key=>$value)
		{
			$this->set($key, $value);
		}
	}

	public function getNode($name, $data = null)
	{
		if (isset($this->blocks[$name]))
		{
			$tmp = $this->data;
			if ($data != null)
			{
				$this->data = $data;
			}

			$result = $this->blocks[$name];
			$result = $this->replace($result);

			$this->data = $tmp;

			return $result;
		} else
		{
			return "[?]";
		}
	}

	public function foreachNode($nodeName, $data)
	{
		if (!is_string($nodeName) || !is_array($data))
		{
			throw new Exception();
		} else
		{
			$content = "";
			foreach ($data as $k=>$value)
			{
				$content .= $this->getNode($nodeName, $value);
			}
			return $content;
		}
	}

	public function right($right = "login")
	{
		$this->neededRight = $right;
	}
	
	public function display($node = null)
	{
		echo $this->get($node);
	}

	public function get($node = null)
	{

		if ($this->error)
		{
			return "<b>Error Occured</b>";
		}

		$ok = false;
		$nr = $this->neededRight;

		if ($nr == null)
		{
			$ok = true;
		} else
		{
			if ($nr == "login")
			{
				$ok = isset($_SESSION["username"]);
			} elseif ($nr == "superadmin")
			{
				$ok = (isset($_SESSION['username']) && in_array($_SESSION['username'], $this->hp->getsuperadmin()));
			} else
			{
				$ok = $this->hp->right->is($nr);
			}

		}

		if ($ok)
		{
			if ($node == null)
			{
				$node = $this->DEFAULT_NODE;
			}

			if (isset($this->blocks[$node]))
			{
				return $this->replace($this->blocks[$node]);
			} else
			{
				return "<b>Node '$node' not found!</b>";
			}

		} else
		{
			$hp = $this->hp;
			$lang = $hp->getlangclass();

			return $lang->word('noright2');
		}
	}

	public function getVars()
	{
		return $this->vars;
	}

	// ------------------------ Static Functions ---------------------------------
	public static function extend($ext)
	{

		if (is_object($ext))
		{
			//$ext->sethp($this->hp);

			$funktions = get_class_methods($ext);

			foreach ($funktions as $key=>$value)
			{
				$split = explode("_", $value, 2);
				if ((count($split) > 1) && ($split[0] == "temp") && ($split[1] != "") && !in_array($split[1], self::$functions))
				{
					$name = $split[1];

					$array = array(
						"name" => $name,
						"func" => $value,
						"obj" => $ext
						);

					self::$functions[$name] = $array;

				}
			}
		}
	}

	// ----------------------------- Template Functions ---------------------------

	public function temp_echo($args)
	{
		$value = "";

		foreach($args as $k=>$v)
		{
			if (is_array($v) || is_object($v))
			{
				$value .= "<pre>";
				$value .= print_r($v, true);
				$value .= "</pre>";
				
			} elseif (is_bool($v))
			{
				$value .= $v;
			} else
			{
				$value .= $v;
			}

		}
		return $value;
	}

	public function temp_inArray($args)
	{
		$argCount = count($args);

		if ($argCount != 2)
		{
			return "[InArray: Need 2 Arguments]";
		} else
		{
			return  in_array($args[0], $args[1]);
		}

	}

	/*
	 * Executes printf
	 *
	 * Parameter:
	 * - Text
	 * * Arguments
	 */
	public function temp_printf($args)
	{
		if (count($args) < 2)
		{
			return "[printf: Need at least 2 Arguments]";
		} else
		{
			$format = array_shift($args);
			return vsprintf($format, $args);
		}

	}


	/*

	 Includes Content from another template File
	 Parameter:
	   - template File
	   - Block (Optional)

	*/
	public function temp_include($args)
	{
		$argCount = count($args);

		$site = new siteTemplate($this->hp, $this);

		if ($argCount < 1)
		{
			return "[Args?]";
		} elseif ($argCount >= 1)
		{
			$site->load($args[0]);
			$path = (isset($this->data['traceback'])) ? ($this->data['traceback'].' -> '.$this->path) : $this->path;

			$site->append('traceback', $path);

			$content = '';

			switch ($argCount)
			{
				case 2:
					$content = $site->get($args[1]);
					break;

				case 1:
				default:
					$content = $site->get();
					break;
			}
			$this->vars = array_merge($this->vars, $site->getVars());
			
			return $content;
		}
	}
	
	
	/*
	   Accsess the Child Element of a Array or Object
	   Parameter:
	       - Array/Object
	       - Key
	       
	*/
	public function temp_child($args)
	{
		if (count($args) < 2)
		{
			return "[Args?]";
		} else
		{
			if (is_array($args[0]) && isset($args[0][$args[1]]))
			{
				return $args[0][$args[1]];
				
			} elseif (is_object($args[0]) && isset($args[0]->$args[1]))
			{
				return $args[0]->$args[1];
				
			} else
			{
				return null;
			}
		}
		
	}
	
	/*
	    Appends a Element to the base Template
	    Parameter:
	        - Key
	        - Value

	*/
	public function temp_appendBase($args)
	{
		if (count($args) < 2)
		{
			return "[Args?]";
		} else
		{
			if (isset($this->hp->template))
			{
				$this->hp->template->append($args[0], $args[1]);
			}
		}

	}
	
	/*
	    Appends a Element to the Template
	    Parameter:
	        - Key
	        - Value

	*/
	public function temp_append($args)
	{
		if (count($args) < 2)
		{
			return "[Args?]";
		} else
		{
			$this->append($args[0], $args[1]);
		}

	}
	
	/*
		Returns the Used-Pics
	
	*/
	public function temp_getUsedPics($args)
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
	
		$pics = array();
		$sql = "SELECT * FROM `$dbprefix"."usedpics`";
		$erg = $hp->mysqlquery($sql);
		while ($row = mysql_fetch_object($erg))
		{

			$breite=$row->width;
			$hoehe=$row->height;

			$neueHoehe=100;
			$neueBreite=intval($breite*$neueHoehe/$hoehe);

			$data = array(
				'ID' => $row->ID,
				'width' => $neueBreite,
				'height' => $neueHoehe
				);
			
			$pics[] = $data;

		}
		
		return $pics;
	}
	
	
}
?>