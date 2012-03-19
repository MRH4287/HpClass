<?php

class subpageTemplate extends siteTemplate
{

	public function __construct($hp)
	{
		parent::__construct($hp);
		$this->searchpath = "subpages/";
		$this->searchpathT = "template/#!Design#/subpages/";

	}

}


?>