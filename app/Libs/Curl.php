<?php
/**
 * Created by helloJiu.
 * Idea: 认真编码 快乐生活
 * Date: 2018/11/27
 * Time: 22:33
 */

namespace App\Libs;

use Curl\Curl as Client;
use Log;

class Curl
{
    private $http = null;

    public function __construct()
    {
        $this->http = new Client();
    }


    public function get($url, $params = []) {
        $this->http->get($url, $params);
        // Log::alert($url);
        // Log::alert(json_encode($this->http->getResponse()));
        return $this->http->getResponse();
    }

    /**
     * @param $url
     * @param string $params array|string(json raw)
     * @return null
     */
    public function post($url, $params='') {
        $this->http->post($url, $params);
        // Log::alert($url);
        // Log::alert($params);
        // Log::alert(json_encode($this->http->getResponse()));
        return $this->http->getResponse();
    }
}