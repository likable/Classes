<?php

namespace classes;

class Parser 
{
    
    private $ch;
    
    public function __construct(int $timeout = 60) 
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
    
    public function getBody(string $url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_NOBODY, false);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        return curl_exec($this->ch);
    }
    
    public function getHeaders(string $url, bool $follow = true)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        curl_setopt($this->ch, CURLOPT_NOBODY, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $follow);
        return curl_exec($this->ch);
    }
    
    public function getStatusCode(string $url, bool $follow = false)
    {
        $this->getHeaders($url, $follow);
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }
}
