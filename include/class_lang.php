<?php
class lang implements arrayaccess
{
	var $lang = array();

	var $clang;
	var $error;
	var $hp;
	var $temppath = "";

	function init($lang2)
	{
		if (!isset($_SESSION['language']))
		{
			$this->setlang($lang2);
		} else
		{
			$this->setlang($_SESSION['language']);
		}
		$this->lang = array();

		$this->incfiles();
		//$this->loadfromdb();

		$config = $this->hp->getconfig();

		if ($config['design'] != "")
		{
			$design = $config['design'];
			$this->temppath= $design;
		}
		$this->inctempfiles();
		
		AjaxFunctions::extend($this);
	}

	/*
	 * DEPRECATED
	function lang1()
	{
	    return $this->lang;
	}
	*/

	function getlang()
	{
		if (isset($this->lang[$this->clang]))
		{
			return $this->lang[$this->clang];
		}
		else
		{
			return array();
		}
	}

	/*
	 * DEPRECATED
	function getlang2($key1)
	{
	$key1 = (string) $key1;
	return $this->lang[$key1];
	}
	*/

	function setlang($lang2)
	{
		$this->clang = $lang2;
		$_SESSION['language'] = $lang2;
	}

	function word($word)
	{       
		$clang = $this->clang;
		
		if (isset($this->lang[$clang]) && isset($this->lang[$clang][$word])) 
		{
			return $this->lang[$clang][$word];
		} else
		{
			$this->error->error("Language File not Found!", "2");
			if ($clang == "dev")
			{
				return "<-$word->";
			} else
			{
				//echo "<pre>";
				//print_r($this->lang[$clang]);
				//echo "</pre>";
				
				return "<-!->";
			}
		}
	}

	// DEPRECATED
	function savetodb($file = true)
	{
		// We don't want to use the Database!
		return;
		
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$fp = $hp->fp;

		if ($file)
		{
			$lang = array();
			$this->incfiles();
		}

		$sql = "TRUNCATE `$dbprefix"."lang`";
		$erg = $hp->mysqlquery($sql);
		
		foreach ($this->lang as $lang=>$langarray) 
		{
			foreach ($langarray as $key=>$value)
			{
				
				$sql = "INSERT INTO `$dbprefix"."lang` (`lang`, `word`, `wort`) VALUES ('$lang', '$key', '$value');";
				$erg = $hp->mysqlquery($sql);     
			}    
		}
		
		$this->incfiles();
		// $this->loadfromdb();
	}
	
	// DEPRECATED
	function loadfromdb()
	{
		$hp = $this->hp;
		$dbprefix = $hp->getprefix();
		$fp = $hp->fp;

		$sql = "SHOW TABLES LIKE '$dbprefix"."lang';";
		$erg = $hp->mysqlquery($sql);
		$row = mysql_fetch_array($erg);
		if ((count($row) >= 1) and ($row!=false))
		{
			$sql = "SELECT * FROM `$dbprefix"."lang`";
			$erg = $hp->mysqlquery($sql);

			while ($row = mysql_fetch_object($erg))
			{
				$this->addword($row->lang, $row->word, $row->wort);
			}
		}
	}

	function addword($lang, $word, $wort)
	{
		$this->lang[$lang][$word] = $wort;
	}


	function word_exsists($word)
	{
		return (isset($this->lang[$this->clang]) && isset($this->lang[$this->clang][$word]));
	}

	public function offsetSet($offset, $value) 
	{
		throw new Exception('Can\'t write to language Object!');
	}
	
	public function offsetExists($offset) 
	{
		return $this->word_exsists($offset);
	}
	
	public function offsetUnset($offset) 
	{
		throw new Exception('Can\'t write to language Object!');
	}
	
	public function offsetGet($offset) 
	{
		return $this->word($offset);
	}
	
	function currentlang()
	{
		return $this->clang;
	}

	function incfiles()
	{
		// Include from the language-files:
		$handle = @opendir(HP::$ROOT_PATH."include/lang/");
		while (false !== ($file = @readdir($handle))) 
		{
			$n= explode(".", $file);
			$art = strtolower($n[count($n) -1]);

			if ($art == "php")
			{
				if (file_exists(HP::$ROOT_PATH."include/lang/$file"))
				{
					include (HP::$ROOT_PATH."include/lang/$file");
				}
				
				$this->addlang($lang);
			}
		}
	}

	function inctempfiles()
	{
		$fp = $this->hp->firephp;

		if ($this->temppath == "")
		{
			$config = $this->hp->getconfig();
			if ($config['design'] != "")
			{
				$this->temppath = $config['design'];
			}
		}


		if (is_dir(HP::$ROOT_PATH."template/".$this->temppath."/lang/"))
		{

			$handle = @opendir(HP::$ROOT_PATH."template/".$this->temppath."/lang/");
			while (false !== ($file = @readdir($handle))) 
			{
				$n= explode(".", $file);
				$art = strtolower($n[count($n) -1]);

				if ($art == "php")
				{
					if (file_exists(HP::$ROOT_PATH."template/".$this->temppath."/lang/$file"))
					{
						include (HP::$ROOT_PATH."template/".$this->temppath."/lang/$file");
					}
					
					$this->addlang($lang);
				}
			}
		}
	}


	function sethp($hp)
	{
		$this->hp = $hp;
	}


	function addlang ($lang)
	{
		$tmp_array = array();

		foreach ($lang as $key=>$value) 
		{
			$key = (string) $key;
			if (isset($this->lang[$key]))
			{
				$tmp_array = $this->lang[$key];
			} else
			{
				$tmp_array = null;
			}

			if (!is_array($tmp_array))
			{
				$tmp_array = array();
			}
			
			$temparray2 = array ();
			$temparray2 = $lang[$key];

			$tmp_array = array_merge($tmp_array, $temparray2);
			$this->lang[$key] = $tmp_array;
		}
	}

	function seterror($error)
	{
		$this->error = $error;
	}


	public function ajax_l10n($args)
	{
		if (isset($args['request']))
		{
			$req = $args['request'];
		
			if (!is_array($req))
			{
				$req = array( $req );
			}
					
			$result = array();
			foreach($req as $key => $name)
			{
				$result[$name] = $this->word($name);
			}
			
			return $result;
		} else
		{
			header("HTTP/1.0 400 Bad Request");
			exit();
		}
	}
}

?>
