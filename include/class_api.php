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
        
        $sql = "UPDATE `$dbprefix"."user` SET `token` = '$token', `counter` = 0, `lastaction` = NOW() WHERE `user` = '$data->user';";
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
    
    try
    {
      
      if (!isset($data->token))
      {
        throw new Exception();
      }
      
      $sql = "SELECT token, counter, user, ID, level, UNIX_TIMESTAMP(`lastaction`) AS `lastaction` FROM `$dbprefix"."user` WHERE `token` = '$data->token';";
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
  
         
          $sql = "UPDATE `$dbprefix"."user` SET `counter` = `counter` + 1, `lastaction` = NOW() WHERE `token` = '$data->token';";
          $erg = $hp->mysqlquery($sql);
          
          
          
          $key = md5(SHARED_SECRET.$row->token.$row->user.SHARED_SECRET.($row->counter +1 ).SHARED_SECRET);
           
          $response = new Response();
          $response->token = $data->token;
          $response->result = $result;
          $response->key = $key;
         
          return  json_encode($response);
          
        
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
      
    } catch(Exception $e)
    {
      
      $response = new Response();
      $response->token = "-1";
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

  
  private function cryptIt($input)
  {
    $key = SHARED_SECRET;
    $key1Mod = ord(substr($key, 0, 1));
    $key2Mod = ord(substr($key, strlen($key) -1, 1));
    
    return $this->crypto($input, $key1Mod, $key2Mod);

  }
  
  private function cryptArray($input)
  {
    $result = array();
    
    for ($i = 0; $i < count($input); $i++)
    {
      $result[$i] = $this->cryptIt($input[$i]);

    }
    return $result;
 
  }
  
  
  private function crypto($input, $key1Mod, $key2Mod)
  {
    
    $result = "";
    $keyLen = strlen(SHARED_SECRET);
    $length = strlen($input);
    
    for ($i = 0; $i < $length; $i++)
    {
      
      $key1 = ord(substr(SHARED_SECRET, ($i % $keyLen), 1));
      $key2 = $length ^ ( $key1 % $key1Mod ) + $i ^ ( $key1 % $key2Mod ) + $key1;
      $key2 = $key2 % 255;
      
      $result .= chr( ord( substr($input, $i, 1) ) ^ $key2 );
    
    }
    
    return $result; 
  }

}

?>