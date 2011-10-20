<?php

class infoclass
{
  var $lang;
  var $error;
  var $hp;
  var $infoarray = array();
  var $okmarray = array();

  var $firephp;

  function init($lang, $error, $hp)
  {
    $this->lang = $lang;
    $this->error = $error;
    $this->hp = $hp;
    $this->firephp = $hp->getfirephp();
    $this->outputdiv();
  }

  function info($info)
  {
   if (!in_array($info, $this->infoarray))
    {
      array_push($this->infoarray, $info);
      if (is_object($this->firephp))
      {
        $this->firephp->info($info);
      }
    }
  }

  function okm($okm)
  {
   if (!in_array($okm, $this->okmarray))
    {
      array_push($this->okmarray, $okm);
      if (is_object($this->firephp))
      {
        $this->firephp->log($okm);
      }
    }
  }

  function okn($okm)
  {
    // Löst das Problem, dass ich mich beim Programmieren verschreib :-D
    $this->okm($okm);
  }


  function outputdiv()
  {
    $site = new siteTemplate($this->hp);
    $site->load("messages");

    $this->hp->template->append("messages", $site->get("Info"));
    $this->hp->template->append("messages", $site->get("Ok"));
  }

  function getmessages()
  {
    $string="";
    $infoarray = $this->infoarray;



    foreach ($infoarray as $key=>$value)
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
      $string = str_replace("'", '\\"', $string);
      $site = new siteTemplate($this->hp);
      $site->load("messages");
      $site->set("message", $string);
      $site->display("Info-Set");

    }


    $string="";
    $okmarray = $this->okmarray;



    foreach ($okmarray as $key=>$value)
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
      $string = str_replace("'", '\"', $string);
      $site = new siteTemplate($this->hp);
      $site->load("messages");
      $site->set("message", $string);
      $site->display("Ok-Set");

    }

  }

}
?>