<?php

class Request
{

  public $user;
  public $key;
  public $verification;

}

class Command
{

  public $token;
  public $key;
  public $command;
  public $arguments;


}

class Initialize
{
  public $loginOk;
  public $token;

}


class Response
{

  public $token;
  public $result;
  public $key;

}


?>