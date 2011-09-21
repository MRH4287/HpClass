<?php

abstract class Api
{

  protected $hp;

  function sethp($hp)
  {
    $this->hp = $hp;
  }



  public function request($data)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $right = $hp->getright();
  
    $data = json_decode($data);
    $ok = true;
    
    
    $sql = "SELECT * FROM `$dbprefix"."user` WHERE `user` = '$data->user';";
    $erg = $hp->mysqlquery($sql);
    if (mysql_num_rows($erg) > 0)
    {  
      $row = mysql_fetch_object($erg);
    
      $key = md5(SHARED_SECRET.$row->user.$row->pass.SHARED_SECRET);
      
      $verification = md5(SHARED_SECRET.$data->key.SHARED_SECRET);
      
      $level = $row->level;
            
      if (!$right[$level]["allow_ScriptAccess"])
      {       
          $ok = false;
          
      } elseif ($data->key != $key)
      {
        if ($verification == $data->verification)
        {
          $response = new Initialize();
          $response->token = $row->ID;
          $response->loginOk = false;
          return json_encode($response);
          
        }  else
        {
          $ok = false;
        }
        
      } else
      {
        // Generierung eines zufälligen Tokens:
        
        $token = $this->getRandomToken();
        
        $time = time();
        
        $sql = "UPDATE `$dbprefix"."user` SET `token` = '$token', `counter` = 0, `lastaction` = '$time' WHERE `user` = '$data->user';";
        $erg = $hp->mysqlquery($sql);
        
        $resp = new Initialize();
        $resp->token = $token;
        $resp->loginOk = true;
        
        return json_encode($resp);
      
      
      }
    } else
    {
      $ok = false;
    }
    
    
    if (!$ok)
    {
      $response = new Initialize();
      $response->token = "-1";
      $response->loginOk = false;
      
      return json_encode($response);
      
    }

  
  }
  
  public function command($data)
  {
    $hp = $this->hp;
    $dbprefix = $hp->getprefix();
    $right = $hp->getright();
  
    $data = json_decode($data);
    $ok = true;
    
        
    $sql = "SELECT * FROM `$dbprefix"."user` WHERE `token` = '$data->token';";
    $erg = $hp->mysqlquery($sql);
    if (mysql_num_rows($erg) > 0)
    {  
      $row = mysql_fetch_object($erg);
    
      $key = md5(SHARED_SECRET.$row->token.$row->user.SHARED_SECRET.$row->counter.SHARED_SECRET);
      
      $level = $row->level;
            
      if (!$right[$level]["allow_ScriptAccess"])
      {       
          $ok = false;

      } elseif (($data->key != $key) || ((time() - $row->lastaction) > 300))
      {
        $ok = false;
        
        // Jemand hat versucht eine Session zu hacken, invalidieren der Session:
        $token = $this->getRandomToken();
        $sql = "UPDATE `$dbprefix"."user` SET `token` = '$token', `counter` = 0 WHERE `token` = '$data->token';";
        $erg = $hp->mysqlquery($sql);
        
        
      } else
      {
       
        // Kommando ausführen:

        $result = $this->executeCommand($data->command, $data->arguments);

        $counter = $row->counter+1;
        
        $time = time();
        
        $sql = "UPDATE `$dbprefix"."user` SET `counter` = '$counter', `lastaction` = '$time' WHERE `token` = '$data->token';";
        $erg = $hp->mysqlquery($sql);
        
        
        
        $key = md5(SHARED_SECRET.$row->token.$row->user.SHARED_SECRET.$counter.SHARED_SECRET);
         
        $response = new Response();
        $response->token = $data->token;
        $response->result = $result;
        $response->key = $key;
       
        return json_encode($response);
        
      
      }
      
    } else
    {
        $ok = false;
    }
      
      
    if (!$ok)
    {
       $response = new Response();
       $response->token = $data->token;
       $response->result = "ERROR: Invalid Data!";
       $response->key = "-1";
        
       return json_encode($response);
        
    }
    
    
  
  }
  

  abstract protected function executeCommand($command, $arguments);


  protected function getRandomToken()
  {
    $password_length = 50;
    $generated_password = "";
    $valid_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!;:-_#";
    $i = 0;
    
    
    $chars_length = strlen($valid_characters) - 1;
    for($i = $password_length; $i--; )
    {
      $generated_password .= $valid_characters[mt_rand(0, $chars_length)];
    }
    
    return $generated_password;   
  
  }



}

?>