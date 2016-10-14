<?php
namespace Pili;

use \Qiniu\Utils;
use \Qiniu\HttpRequest;
use \Qiniu\Credentials;
use \Pili\Config;

final class Transport
{
    private $_credentials;

    public function __construct($credentials)
    {
    	$this->_credentials = $credentials; 
    }

    public function getCredentials()
    {
        return $this->_credentials;
    }

    public function send($method, $url, $body = NULL)
    {
        $headers = $this->_setHeaders($method, $url, $body);
        $response = HttpRequest::send($method, $url, $body, $headers);
        return $response->body;
    }

    private function _setHeaders($method, $url, $body = NULL)
    {
        $ctType = 'application/json';
        $macToken = $this->_credentials->MACToken($method, $url, $ctType, $body);
        $ua = Utils::getUserAgent(Config::SDK_USER_AGENT, Config::SDK_VERSION);
        return array(
            'Content-Type'  => $ctType,
            'User-Agent'    => $ua,
            'Authorization' => $macToken,
        );
    }

}

?>

