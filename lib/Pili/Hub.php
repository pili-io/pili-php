<?php
namespace Pili;

use \Qiniu\HttpRequest;
use \Qiniu\Utils;


class Hub
{
    private $_hub;
    private $_baseURL;
    private $_transport;

    public function __construct($mac, $hubName)
    {
        $this->_hub = $hubName;
        $this->_transport = new Transport($mac);

        $cfg = Config::getInstance();
        $protocal = $cfg->USE_HTTPS === true ? "https" : "http";
        $this->_baseURL = $protocal . "://" . $cfg->API_HOST . "/v2/hubs/" . $this->_hub;
    }

    public function create($streamKey)
    {
        $url = $this->_baseURL . "/streams";
        $params['key'] = $streamKey;
        $body = json_encode($params);
        try {
            $this->_transport->send(HttpRequest::POST, $url, $body);
        } catch (\Exception $e) {
            return $e;
        }

        return new Stream($this->_transport, $this->_hub, $streamKey);
    }

    public function stream($streamKey)
    {
        return new Stream($this->_transport, $this->_hub, $streamKey);
    }

    private function _list($live, $prefix, $limit, $marker)
    {
        $url = sprintf("%s/streams?liveonly=%s&prefix=%s&limit=%d&marker=%s", $this->_baseURL, $live, $prefix, $limit, $marker);
        try {
            $ret = $this->_transport->send(HttpRequest::GET, $url);
        } catch (\Exception $e) {
            return $e;
        }
        $keys = array();
        foreach ($ret["items"] as $item) {
            array_push($keys, $item["key"]);
        }
        $ret = array();
        $ret["keys"] = $keys;
        $ret["omarker"] = $ret["marker"];
        return $ret;
    }

    public function listLiveStreams($prefix, $limit, $marker)
    {
        return $this->_list("true", $prefix, $limit, $marker);
    }

    public function listStreams($prefix, $limit, $marker)
    {
        return $this->_list("false", $prefix, $limit, $marker);
    }
}

//----------------url
function RTMPPublishURL($domain, $hub, $streamKey, $expireAfterSeconds, $accessKey, $secretKey)
{
    $expire = time() + $expireAfterSeconds;
    $path = sprintf("/%s/%s?e=%d", $hub, $streamKey, $expire);
    $token = $accessKey . ":" . Utils::sign($secretKey, $path);
    return sprintf("rtmp://%s%s&token=%s", $domain, $path, $token);
}

function RTMPPlayURL($domain, $hub, $streamKey)
{
    return sprintf("rtmp://%s/%s/%s", $domain, $hub, $streamKey);
}

function HLSPlayURL($domain, $hub, $streamKey)
{
    return sprintf("http://%s/%s/%s.m3u8", $domain, $hub, $streamKey);
}

function HDLPlayURL($domain, $hub, $streamKey)
{
    return sprintf("http://%s/%s/%s.flv", $domain, $hub, $streamKey);
}

function SnapshotPlayURL($domain, $hub, $streamKey)
{
    return sprintf("http://%s/%s/%s.jpg", $domain, $hub, $streamKey);
}

?>