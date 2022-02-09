<?php
/**
 * @author Jehan Afwazi Ahmad <jehan.afwazi@gmail.com>.
 */


namespace App\Cores;


use GuzzleHttp\Client;

class ZHttpClient extends Client
{
    private $url;

    public static function init($url)
    {
        $me = new self();
        $me->url = $url;
        return $me;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function url($url)
    {
        return $this->url . "/$url";
    }
}