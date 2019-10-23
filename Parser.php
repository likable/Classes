<?php

namespace classes;

class Parser 
{
    
    private $ch;
    
    public function __construct($timeout = 60) 
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
    }
    
    public function __destruct() 
    {
        curl_close($this->ch);
    }
    
    public function setopt($name, $value)
    {
        if (!curl_setopt($this->ch, $name, $value)) {
            die("Setopt {$name} failed!");
        }
    }
    
    public function getBody($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return curl_exec($this->ch);
    }
    
    
    
    
    
    //getHeaders
    
    
    
    
    //getStatusCode
    
    
    
    
    
    
    
    
    
    
    
    
    
}
