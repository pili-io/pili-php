<?php
namespace Pili;

use \Qiniu\Utils;
use \Pili\Api;

class Stream
{
    private $_transport;
    private $_data;

    public function __construct($transport, $streamData = array())
    {
        $this->_data = $streamData;

        $this->_transport = $transport;

        if (empty($streamData) || !is_array($streamData)) {
            throw new \Exception('invalid args');
        }
    }

    public function __get($property)
    {
        if (isset($this->_data[$property]))
        {
            return $this->_data[$property];
        }
        else
        {
            return NULL;
        }
    }

    public function __set($property, $value)
    {
        if (isset($this->_data[$property]))
        {
            $this->_data[$property] = $value;
        }
        return $this;
    }

    public function toJSONString()
    {
        return json_encode($this->_data);
    }

    public function status()
    {
        return Api::streamStatus($this->_transport, $this->id);
    }

    public function update()
    {
        $stream = Api::streamUpdate($this->_transport, $this->id, $this->_data);
        return new Stream($this->_transport, $stream);
    }

    public function disable($disabledTill = NULL)
    {
        return Api::streamAvailable($this->_transport, $this->id, "disabled", $disabledTill);
    }

    public function enable()
    {
        return Api::streamAvailable($this->_transport, $this->id, "enabled");
    }

    public function delete()
    {
        return Api::streamDelete($this->_transport, $this->id);
    }

    public function segments($start = NULL, $end = NULL, $limit = NULL)
    {
        return Api::streamSegments($this->_transport, $this->id, $start, $end, $limit);
    }

    public function saveAs($name, $format = NULL, $start = NULL, $end = NULL, $notifyUrl = NULL, $pipeline = NULL)
    {
        return Api::streamSaveAs($this->_transport, $this->id, $name, $format, $start, $end, $notifyUrl, $pipeline);
    }

    public function snapshot($name, $format, $time = NULL, $notifyUrl = NULL, $pipeline = NULL)
    {
        return Api::streamSnapshot($this->_transport, $this->id, $name, $format, $time, $notifyUrl, $pipeline);
    }

    // Publish URL
    // -------------------------------------------------------------------------------
    public function rtmpPublishUrl()
    {
        switch ($this->publishSecurity)
        {
            case 'static':
                $url = $this->_rtmpPublishStaticUrl();
                break;
            case 'dynamic':
                $url = $this->_rtmpPublishDynamicUrl();
                break;
            case 'expiry_sk':
                $url = $this->_rtmpPublishExpireUrl();
                break;
            default:
                $url = $this->_rtmpPublishBaseUrl();
                break;
        }
        return $url;
    }

    private function _rtmpPublishDynamicUrl()
    {
        $nonce = time();
        $url = sprintf("%s?nonce=%d&token=%s", $this->_rtmpPublishBaseUrl(), $nonce, $this->_publishDynamicToken($nonce));
        return $url;
    }

    private function _rtmpPublishExpireUrl()
    {
        $expire = time() + 3600;
        $token =  $this->_publishExpireToken($expire);
        $url = sprintf("%s?e=%s&token=%s", $this->_rtmpPublishBaseUrl(), $expire, $token);
        return $url;
    }

    private function _rtmpPublishStaticUrl()
    {
        $url = sprintf("%s?key=%s", $this->_rtmpPublishBaseUrl(), $this->publishKey);
        return $url;
    }

    private function _rtmpPublishBaseUrl()
    {
        $url = sprintf("rtmp://%s/%s/%s", $this->hosts["publish"]["rtmp"], $this->hub, $this->title);
        return $url;
    }

    private function _publishDynamicToken($nonce)
    {
        $url = parse_url($this->_rtmpPublishBaseUrl());
        $data = $url['path'];
        $separator = empty($url['query']) ? '?' : '&';
        if (!empty($url['query']))
        {
            $data .= $separator . $url['query'];
        }
        $data .= $separator . 'nonce=' . $nonce;
        $publishToken = Utils::sign($this->publishKey, $data);
        return $publishToken;
    }

    private function _publishExpireToken($expire)
    {
        $path = "/{$this->hub}/{$this->title}?e={$expire}";
        $credentials = $this->_transport->getCredentials();
        return $credentials->_accessKey . ":" . Utils::sign($credentials->_secretKey, $path);
    }

    // RTMP Live Play URLs
    // --------------------------------------------------------------------------------
    public function rtmpLiveUrls()
    {
        $urls = array();
        $url = sprintf("rtmp://%s/%s/%s", $this->hosts["live"]["rtmp"], $this->hub, $this->title);
        $urls['ORIGIN'] = $url;
        if (isset($this->profiles) && !empty($this->profiles)) {
            foreach ($this->profiles as $profile) {
                $urls[$profile] = sprintf("%s@%s", $url, $profile);
            }
        }
        return $urls;
    }

    // HTTP Live Streaming Play URLs
    // --------------------------------------------------------------------------------
    public function hlsLiveUrls()
    {
        $urls = array();
        $urls['ORIGIN'] = sprintf("http://%s/%s/%s.m3u8", $this->hosts["live"]["hls"], $this->hub, $this->title);
        if (isset($this->profiles) && !empty($this->profiles)) {
            foreach ($this->profiles as $profile) {
                $urls[$profile] = sprintf("http://%s/%s/%s@%s.m3u8", $this->hosts["live"]["hls"], $this->hub, $this->title, $profile);
            }
        }
        return $urls;
    }

    // HTTP-Flv Live Play URLs
    // --------------------------------------------------------------------------------
    public function httpFlvLiveUrls()
    {
        $urls = array();
        $urls['ORIGIN'] = sprintf("http://%s/%s/%s.flv", $this->hosts["live"]["hdl"], $this->hub, $this->title);
        if (isset($this->profiles) && !empty($this->profiles)) {
            foreach ($this->profiles as $profile) {
                $urls[$profile] = sprintf("http://%s/%s/%s@%s.flv", $this->hosts["live"]["hdl"], $this->hub, $this->title, $profile);
            }
        }
        return $urls;
    }

    // HLS Playback URLs
    // --------------------------------------------------------------------------------
    public function hlsPlaybackUrls($start = -1, $end = -1)
    {
        $name = sprintf("%d", time());
        $resp = $this->saveAs($name, NULL, $start, $end);
        $urls = array();
        $urls['ORIGIN'] = $resp["url"];
        return $urls;
    }
}
?>

