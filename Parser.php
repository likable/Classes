<?php

namespace classes;

class Parser 
{
    
    private $ch;
    private $cookie_path;
    private $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36";
    
    public function __construct(int $timeout = 30) 
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->user_agent);
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
        
        $this->setCookies($url);
        
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
    
    private function setCookies(string $url)
    {
        $this->cookie_path = __DIR__ . "/cookies" . time() . ".txt";
        
        //создание вспомогательного дескриптора
        $ch_cookies = curl_init($url);
        curl_setopt($ch_cookies, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_cookies, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_cookies, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch_cookies, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch_cookies, CURLOPT_HEADER, true);
        curl_setopt($ch_cookies, CURLOPT_NOBODY, true);
        curl_setopt($ch_cookies, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch_cookies, CURLOPT_USERAGENT, $this->user_agent);
        
        //создание файла с куками
        curl_setopt($ch_cookies, CURLOPT_COOKIEJAR, $this->cookie_path);
        curl_exec($ch_cookies);
        curl_close($ch_cookies);
        
        //установка кук для главного дескриптора
        if (file_exists($this->cookie_path)) {
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_path);
            unlink($this->cookie_path);
        } else {
            die("Файл с куками не найден!");
        }
    }
    
    public function setUserAgent(string $user_agent)
    {
        $this->user_agent = $user_agent;
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->user_agent);
    }
    
    public function getUserAgent()
    {
        return $this->user_agent;
    }
    

    
    
    
    
    
    
}
