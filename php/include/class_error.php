<?php
class Errorclass
{
	var $errorm;
	var $errorarray = array();
	var $hp;

	function error($string, $l = "2", $function = "")
	{


		$l = (string) $l;
		if ($l == "1")
		{
			echo $string;

		} elseif ($l == "2")
		{

			$this->errorm = $string;

			if (!in_array($string, $this->errorarray))
			{
				array_push($this->errorarray, $string);

			}



		} elseif ($l == "3")
		{

			$datum = date('j').".".date('n').".".date('y');

			echo "<font color=red>$string</font>";

			$this->hp->PM('mrh', 'System', 'ERROR der Stufe 3!', $string, $datum);

			exit;

		}
	}

	function geterror()
	{
		return $this->errorm;
	}

	function outputdiv()
	{
		$site = new siteTemplate($this->hp);
		$site->load("messages");

		$this->hp->template->append("messages", $site->get("Error"));

	}

	function showerrors()
	{

		$string="";
		$errorarray = $this->errorarray;



		foreach ($errorarray as $key=>$value)
		{
			if ($string <> "")
			{
				$string = $string . ", ". $value;	
			} else
			{
				$string = $value;
			}
		}
		if ($string <> "")
		{
			$string = str_replace("'", '"', $string);

			$site = new siteTemplate($this->hp);
			$site->load("messages");
			$site->set("message", $string);
			$site->display("Error-Set");
		}

	}

	function sethp($hp)
	{
		$this->hp=$hp;
		$this->outputdiv();
	}

}
?>