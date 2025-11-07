<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

if (! function_exists('rewriteRoute')) {
    function rewriteRoute($url,$slug_url=null)
    {

    if(strpos($_SERVER['HTTP_HOST'], "abc.com")  !== false)
    {
        if($slug_url)
        {
        return $url;
        }
        else
        {
        
        $string = explode("abc.com",$url);

        if($string[0] == "https://" || $string[0] == "http://" ||  $string[0] == "http://www." ||  $string[0] == "https://www.")
        {
            return $url;
        }
        else
        {
            $string = explode("//",$string[0]);
            if(strpos($string[1],'www.') !== false)
            {
            $string =  explode(".",$string[1]);
            $slug = $string[1];
            return $url = str_replace($slug.".","www.",$url) ;
            }
            else
            {
            $slug = $string[1];
            return $url = str_replace($slug,"www.",$url) ;
            }
        }
        
        }

    }
    else
    {
        return $url;
    }
    }
}
if (! function_exists('generateShortCode')) {
    function generateShortCode($length = 6)
    {
        return Str::random($length);
    }
}
