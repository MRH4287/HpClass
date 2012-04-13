<?php

class subpageTemplate extends siteTemplate
{

	public function __construct($hp)
	{
		parent::__construct($hp);
		$this->searchpath = HP::$ROOT_PATH."subpages/";
		$this->searchpathT = HP::$ROOT_PATH."template/#!Design#/subpages/";

	}

}


?>