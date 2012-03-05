<?php

class javascriptTemplate extends siteTemplate
{
    
    public function __construct($hp, $path, $copy = null)
    {
        parent::__construct($hp, $copy);
        
        $this->searchpath = $path."template/sites";
        $this->searchpathT = $path."template/#!Design#/sites";  
    }
    
    public function serialize($node = null, $all = true)
    {
        $content = ($node != null) ? $this->get($node) : "";
        
        if ($all)
        {
            return array(
                "name" => $this->name,
                "autor" => $this->autor,
                "blocks" => $this->blocks,
                "data" => $this->data,
                "vars" => $this->vars,
                "content" => $content
            );
        } else
        {
            return array(
                "content" => $content
            );
        }
    
    }
}


?>