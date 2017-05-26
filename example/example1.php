<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili.php')));

// Configuration

// Replace with your keys here
define('ACCESS_KEY', 'Qiniu_AccessKey');
define('SECRET_KEY', 'Qiniu_SecretKey');

// Replace with your hub name
define('HUB', 'Pili_Hub_Name'); // The Hub must be exists before use


// Change API host as necessary
// 
// pili.qiniuapi.com as default
// pili-lte.qiniuapi.com is the latest RC version
//
// $cfg = \Pili\Config::getInstance();
// $cfg->API_HOST = 'pili.qiniuapi.com'; // default


// Hub

// Instantiate an Hub object
$credentials = new \Qiniu\Credentials(ACCESS_KEY, SECRET_KEY); #=> Credentials Object
$hub = new \Pili\Hub($credentials, HUB); # => Hub Object


// Create a new Stream
try {

    $title           = NULL;     // optional, auto-generated as default
    $publishKey      = NULL;     // optional, auto-generated as default
    $publishSecurity = NULL;     // optional, can be "dynamic" or "static", "dynamic" as default

    $stream = $hub->createStream($title, $publishKey, $publishSecurity); # => Stream Object

    echo "createStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo 'createStream() failed. Caught exception: ',  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'pili-publish.example.com',
      ),
      'live' => 
      array (
        'rtmp' => 'pili-live-rtmp.example.com',
        'hls' => 'pili-live-hls.example.com',
        'hdl' => 'pili-live-hdl.example.com',
      ),
      'playback' => 
      array (
        'hls' => 'pili-playback.example.com',
      ),
    ),
  ),
))
*/


// Get Stream
try {

    $streamId = $stream->id;

    $stream = $hub->getStream($streamId); # => Stream Object

    echo "getStream() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "getStream() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'pili-publish.example.com',
      ),
      'live' => 
      array (
        'rtmp' => 'pili-live-rtmp.example.com',
        'hls' => 'pili-live-hls.example.com',
        'hdl' => 'pili-live-hdl.example.com',
      ),
      'playback' => 
      array (
        'hls' => 'pili-playback.example.com',
      ),
    ),
  ),
))
*/


// List streams
try {

    $marker       = NULL;      // optional
    $limit        = NULL;      // optional
    $title_prefix = NULL;      // optional
    $status       = NULL;      // optional, "connected" only

    $result = $hub->listStreams($marker, $limit, $title_prefix, $status); # => Array

    echo "listStreams() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "listStreams() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'marker' => '2',
  'end' => true,
  'items' => 
  array (
      0 => Stream Object,
      1 => Stream Object,
  )  
)
*/


// Stream

// To JSON string
$result = $stream->toJSONString(); # => string
echo "Stream toJSONString() =>\n";
var_export($result);
echo "\n\n";
/*
'{
    "id":"z1.coding.55d7a219e3ba5723280000b5",
    "createdAt":"2015-08-21T18:11:37.057-04:00",
    "updatedAt":"2015-08-21T18:30:32.548-04:00",
    "title":"55d7a219e3ba5723280000b5",
    "hub":"coding",
    "disabled":false,
    "publishKey":"734de946-11e0-487a-8627-30bf777ed5a3",
    "publishSecurity":"dynamic",
    "hosts":{
        "publish":{"rtmp":"pili-publish.example.com"},
        "live":{
            "rtmp":"pili-live-rtmp.example.com",
            "hls":"pili-live-hls.example.com",
            "hdl":"pili-live-hdl.example.com"
        },
        "playback":{
            "hls":"pili-playback.example.com"
        }
    }
}'
*/


// Update a Stream
try {

    $stream->publishKey      = 'new_secret_words'; // optional
    $stream->publishSecurity = 'static';           // optional, can be "dynamic" or "static"
    $stream->disabled        = NULL;               // optional, can be "true" of "false"

    $stream = $stream->update(); # => Stream Object

    echo "Stream update() =>\n";
    var_export($stream);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream update() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
Pili\Stream::__set_state(array(
   '_data' => 
  array (
    'id' => 'z1.coding.55d7a219e3ba5723280000b5',
    'createdAt' => '2015-08-21T18:11:37.057-04:00',
    'updatedAt' => '2015-08-21T18:32:05.186076957-04:00',
    'title' => '55d7a219e3ba5723280000b5',
    'hub' => 'coding',
    'disabled' => false,
    'publishKey' => 'new_secret_words',
    'publishSecurity' => 'static',
    'hosts' => 
    array (
      'publish' => 
      array (
        'rtmp' => 'pili-publish.example.com',
      ),
      'live' => 
      array (
        'rtmp' => 'pili-live-rtmp.example.com',
        'hls' => 'pili-live-hls.example.com',
        'hdl' => 'pili-live-hdl.example.com',
      ),
      'playback' => 
      array (
        'hls' => 'pili-playback.example.com',
      ),
    ),
  ),
))
*/


// Disable a Stream
$disabledTill = time() + 10; # disabled in 10s from now
$result = $stream->disable($disabledTill); # => NULL
echo "Stream disable() =>\n";
var_export($result);
echo "\n\n";
/*
true
*/


// Enable a Stream
$result = $stream->enable(); # => NULL
echo "Stream enable() =>\n";
var_export($result);
echo "\n\n";
/*
false
*/


// Get Stream status
try {

    $result = $stream->status(); # => Array

    echo "Stream status() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream status() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  "reqId" => "YmMxOTcuAAASDc1n",
  "hub" => "coding",
  "stream" => "2b20838cdb214448b7c7eef46abf1a0a",
  "startFrom" => "2015-12-03T12:24:30.226Z",
  'addr' => '222.73.202.226:2572',
  'status' => 'connected',
  'bytesPerSecond' => 16870.200000000001,
  'framesPerSecond' => 
  array (
    'audio' => 42.200000000000003,
    'video' => 14.733333333333333,
    'data' => 0.066666666666666666,
  ),
)
*/


// Generate RTMP publish URL
$publishUrl = $stream->rtmpPublishUrl();
echo "Stream rtmpPublishUrl() =>\n";
echo $publishUrl;
echo "\n\n";
/*
rtmp://pili-publish.example.com/coding/55d7a219e3ba5723280000b5?key=new_secret_words
*/


// Generate RTMP live play URLs
$urls = $stream->rtmpLiveUrls();
echo "Stream rtmpLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'rtmp://pili-live-rtmp.example.com/coding/55d7a219e3ba5723280000b5',
)
*/


// Generate HLS play URLs
$urls = $stream->hlsLiveUrls();
echo "Stream hlsLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://pili-live-hls.example.com/coding/55d7a219e3ba5723280000b5.m3u8',
)
*/


// Generate Http-Flv live play URLs
$urls = $stream->httpFlvLiveUrls();
echo "Stream httpFlvLiveUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://pili-live-hdl.example.com/coding/55d7a219e3ba5723280000b5.flv',
)
*/


// Get Stream segments
try {

    $start = NULL;    // optional, in second, unix timestamp
    $end   = NULL;    // optional, in second, unix timestamp
    $limit = NULL;    // optional, uint

    $result = $stream->segments($start, $end, $limit); # => Array

    echo "Stream segments() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream segments() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'start' => 1440196065,
  'end' => 1440198092,
  'segments' => 
  array (
    0 => 
    array (
      'start' => 1440196065,
      'end' => 1440196124,
    ),
    1 => 
    array (
      'start' => 1440198072,
      'end' => 1440198092,
    ),
  ),
)
*/


// Generate HLS playback URLs
$start     = -1;  // optional, in second, unix timestamp
$end       = -1;  // optional, in second, unix timestamp

$urls = $stream->hlsPlaybackUrls($start, $end);
echo "Stream hlsPlaybackUrls() =>\n";
var_export($urls);
echo "\n\n";
/*
array (
  'ORIGIN' => 'http://pili-playback.example.com/coding/55d7a219e3ba5723280000b5.m3u8?start=-1&end=-1',
)
*/


// Snapshot Stream
try {

    $name      = 'imageName.jpg'; // required
    $format    = 'jpg';           // required
    $time      = NULL;            // optional, in second, unix timestamp
    $notifyUrl = NULL;            // optional
    $pipeline  = NULL;            // optional

    $result = $stream->snapshot($name, $format, $time, $notifyUrl, $pipeline); # => Array

    echo "Stream snapshot() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream snapshot() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'targetUrl' => 'http://pili-static.example.com/snapshots/z1.coding.55d7a219e3ba5723280000b5/imageName.jpg',
  'persistentId' => 'z1.55d7a6e77823de5a49a8899a',
)
*/


// Save Stream as a file
try {

    $name      = 'videoName.mp4'; // required
    $format    = NULL;            // optional
    $start     = -1;              // optional, in second, unix timestamp
    $end       = -1;              // optional, in second, unix timestamp
    $notifyUrl = NULL;            // optional
    $pipeline  = NULL;            // optional

    $result = $stream->saveAs($name, $format, $start, $end, $notifyUrl, $pipeline); # => Array

    echo "Stream saveAs() =>\n";
    var_export($result);
    echo "\n\n";

} catch (Exception $e) {
    echo "Stream saveAs() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
array (
  'url' => 'http://pili-media.example.com/recordings/z1.coding.55d7a219e3ba5723280000b5/videoName.m3u8',
  'targetUrl' => 'http://pili-vod.example.com/recordings/z1.coding.55d7a219e3ba5723280000b5/videoName.mp4',
  'persistentId' => 'z1.55d7a6e77823de5a49a8899b',
)
*/
// curl -D GET http://api.qiniu.com/status/get/prefop?id={persistentId}


// Delete a Stream
try {
    $result = $stream->delete(); # => NULL
    echo "Stream delete() =>\n";
    var_dump($result);
    echo "\n\n";
} catch (Exception $e) {
    echo "Stream delete() failed. Caught exception: ",  $e->getMessage(), "\n";
}
/*
NULL
*/

?>
