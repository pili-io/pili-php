# Pili Streaming Cloud server-side library for PHP

## Features

- URL
    - [x] RTMP推流地址: RTMPPublishURL(domain, hub, streamKey, mac, expireAfterSeconds)
    - [x] RTMP直播地址: RTMPPlayURL(domain, hub, streamKey)
    - [x] HLS直播地址: HLSPlayURL(domain, hub, streamKey)
    - [x] HDL直播地址: HDLPlayURL(domain, hub, streamKey)
    - [x] 截图直播地址: SnapshotPlayURL(domain, hub, streamKey)
- Hub
    - [x] 创建流: hub->create(streamKey)
    - [x] 获得流: hub->stream(streamKey)
    - [x] 列出流: hub->listLiveStreams(prefix, limit, marker)
    - [x] 列出正在直播的流: hub->listStreams(prefix, limit, marker)
- Stream
    - [x] 流信息: stream->info()
    - [x] 启用流: stream->enable()
    - [x] 禁用流: stream->disable()
    - [x] 查询直播状态: stream->liveStatus()
    - [x] 保存直播回放: stream->save(start, end)
    - [x] 查询直播历史: stream->historyActivity(start, end)



## Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Configuration](#configuration)
    - [URL](#url)
        - [Generate RTMP publish URL](#generate-rtmp-publish-url)
        - [Generate RTMP play URL](#generate-rtmp-play-url)
        - [Generate HLS play URL](#generate-hls-play-url)
        - [Generate HDL play URL](#generate-hdl-play-url)
        - [Generate snapshot play URL](#generate-snapshot-play-url)
    - [Hub](#hub)
        - [Instantiate a pili hub object](#instantiate-a-pili-hub-object)
        - [Create a new stream](#create-a-new-stream)
        - [Get a stream](#get-a-stream)
        - [List streams](#list-streams)
        - [List live streams](#list-live-streams)
    - [Stream](#stream)
        - [Get stream info](#get-stream-info)
        - [Disable a stream](#disable-a-stream)
        - [Enable a stream](#enable-a-stream)
        - [Get stream live status](#get-stream-live-status)
        - [Get stream history activity](#get-stream-history-activity)
        - [Save stream live playback](#save-stream-live-playback)


## Installation

### Requirements

- PHP >= 5.3.0

### Install with Composer

If you're using [Composer](http://getcomposer.org) to manage dependencies, you can add pili-sdk-php with it.

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

You can add Pili as a dependency using the `composer.phar` CLI:

```bash
php composer.phar require pili-engineering/pili-sdk-php:dev-master
```

Alternatively, you can specify pili-sdk-php as a dependency in your project's
existing `composer.json` file:

```js
{
    "require": {
        "pili-engineering/pili-sdk-php": "dev-master"
    }
}
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can find out more on how to install Composer, configure autoloading, and
other best-practices for defining dependencies at <http://getcomposer.org>.

### Install source from GitHub

The `pili-sdk-php` requires PHP `v5.3+`. Download the PHP library from Github, and require in your script like so:

To install the source code:

```bash
$ git clone https://github.com/pili-engineering/pili-sdk-php.git
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```

### Install source from zip/tarball

Alternatively, you can fetch a [tarball](https://github.com/pili-engineering/pili-sdk-php/tarball/master) or [zipball](https://github.com/pili-engineering/pili-sdk-php/zipball/master):

```bash
$ curl -L https://github.com/pili-engineering/pili-sdk-php/tarball/master | tar xzv

(or)

$ wget https://github.com/pili-engineering/pili-sdk-php/tarball/master -O - | tar xzv
```

And include it in your scripts:

```php
require_once '/path/to/pili-sdk-php/lib/Pili.php';
```


## Usage

### Configuration

```php
    // Change API host as necessary
    //
    // pili.qiniuapi.com as default
    // pili-lte.qiniuapi.com is the latest RC version
    //
    // $cfg = \Pili\Config::getInstance();
    // $cfg->API_HOST = 'pili.qiniuapi.com'; // default
```


### Url

#### Generate RTMP publish URL

```php
    $url=$stream->RTMPPublishURL("publish-rtmp.test.com", $hubName, $streamKey, 3600,$ak,$sk);
    /*
    rtmp://publish-rtmp.test.com/PiliSDKTest/streamkey?e=1463023142&token=7O7hf7Ld1RrC_fpZdFvU8aCgOPuhw2K4eapYOdII:-5IVlpFNNGJHwv-2qKwVIakC0ME=
    */
```


#### Generate RTMP play URL

```php
    $url=$stream->RTMPPlayURL("live-rtmp.test.com", $hubName, $streamKey);
    /*
    rtmp://live-rtmp.test.com/PiliSDKTest/streamkey
    */
```


#### Generate HLS play URL

```php
    $url=$stream->HLSPlayURL("live-hls.test.com", $hubName, $streamKey);
    /*
    http://live-hls.test.com/PiliSDKTest/streamkey.m3u8
    */
```


#### Generate HDL play URL

```php
    $url=$stream->HDLPlayURL("live-hdl.test.com", $hubName, $streamKey);
    /*
    http://live-hdl.test.com/PiliSDKTest/streamkey.flv
    */
```


#### Generate snapshot play URL

```php
    $url=$stream->SnapshotPlayURL("live-snapshot.test.com", $hubName, $streamKey);
    /*
    http://live-snapshot.test.com/PiliSDKTest/streamkey.jpg
    */
```


### Hub

#### Instantiate a pili hub object

```php
    // Instantiate an Hub object
    $ak = "7O7hf7Ld1RrC_fpZdFvU8aCgOPuhw2K4eapYOdII";
    $sk = "6Rq7rMSUHHqOgo0DJjh15tHsGUBEH9QhWqqyj4ka";
    $hubName = "PiliSDKTest";
    $mac = new Qiniu\Mac($ak, $sk);
    $client = new Pili\Client($mac);
    $hub = $client->hub($hubName);
```


#### Create a new stream

```php
    try{
        $streamKey="php-sdk-test".time();
        $resp=$hub->create($streamKey);
        print_r($resp);
    }catch(\Exception $e) {
             echo "Error:",$e;
    }
    /*
    {hub:hubname,key:streamkey,disabled:false}
    */
```


#### Get a stream

```php
    try{
        $streamKey="php-sdk-test".time();
        $resp=$hub->stream($streamKey);
        print_r($resp);
    }catch(\Exception $e) {
             echo "Error:",$e;
    }
    /*
    {hub:hubname,key:streamkey,disabled:false}
    */
```


#### List streams

```php
    try{
        $streamKey="php-sdk-test".time();
        $resp=$hub->listStreams($streamKey, 1, "");
        print_r($resp);
    }catch(\Exception $e) {
             echo "Error:",$e;
    }
    /*
    keys=[streamkey] marker=
    */
```


#### List live streams

```php
    try{
        $streamKey="php-sdk-test".time();
        $resp=$hub->listLiveStreams($streamKey, 1, "");
        print_r($resp);
    }catch(\Exception $e) {
             echo "Error:",$e;
    }
    /*
    keys=[streamkey] marker=
    */
```


### Stream

#### Get stream info

```php
    try{
        $resp = $stream->info();
    }catch(\Exception $e) {
       echo "Error:",$e;
    }
    /*
    {hub:PiliSDKTest,key:streamkey,disabled:false}
    */
```


#### Disable a stream

```php
    try{
        $resp = $stream->info();
        print_r($resp);
        $stream->disable();
        $resp = $stream->info();
        print_r($resp);
    }catch(\Exception $e) {
       echo "Error:",$e;
    }
    /*
    before disable: {hub:PiliSDKTest,key:streamkey,disabled:false}
    after disable: {hub:PiliSDKTest,key:streamkey,disabled:true}
    */
```


#### Enable a stream

```php
    try{
        $resp = $stream->info();
        print_r($resp);
        $stream->enable();
        $resp = $stream->info();
        print_r($resp);
    }catch(\Exception $e) {
       echo "Error:",$e;
    }
    /*
    before enable: {hub:PiliSDKTest,key:streamkey,disabled:true}
    after enable: {hub:PiliSDKTest,key:streamkey,disabled:false}
    */
```


#### Get stream live status

```php
   try{
       $status=$stream->liveStatus();
       print_r($status);
   }catch(\Exception $e) {
       echo "Error:",$e;
   }
   /*
   {StartAt:1463382400 ClientIP:172.21.1.214:52897 BPS:128854 FPS:{Audio:38 Video:23 Data:0}}
   */
```


#### Get stream history activity

```php
    $records= $stream->historyActivity(0,0);
    print_r($records);
    /*
    [{1463382401 1463382441}]
    */
```


#### Save stream live playback

```php
    try{
        $fname=$stream->save(0,0);
        print_r($fname);
    }catch(\Exception $e) {
        echo "Error:",$e;
    }
    /*
    recordings/z1.PiliSDKTest.streamkey/1463156847_1463157463.m3u8
    */
```


## History
- 2.0.0
    - pili.v2
- 1.5.4
    - Use $stream->saveAs in $stream->hlsPlaybackUrls

- 1.5.3
    - Update $stream->disable($disabledTill)

- 1.5.2
    - Update $stream->rtmpPublishUrl()

- 1.5.1
    - Update API
        - $hub->listStreams($marker=NULL, $limit=NULL, $title_prefix=NULL, $status=NULL)
        - $stream->saveAs($name, $format=NULL, $start=NULL, $end=NULL, $notifyUrl=NULL, $pipeline=NULL)
        - $stream->snapshot($name, $format, $time=NULL, $notifyUrl=NULL, $pipeline=NULL)
        - $stream->hlsPlaybackUrls($start=-1, $end=-1)
- 1.5.0
    - Add Credentials and Transport class
    - Renamed $client to $hub
- 1.4.0
    - Add Stream Create,Get,List
        - $hub->createStream()
        - $hub->getStream()
        - $hub->listStreams()
    - Add Stream operations else
        - $stream->toJSONString()
        - $stream->update()
        - $stream->disable()
        - $stream->enable()
        - $stream->status()
        - $stream->segments()
        - $stream->rtmpPublishUrl()
        - $stream->rtmpLiveUrls()
        - $stream->hlsLiveUrls()
        - $stream->httpFlvLiveUrls()
        - $stream->hlsPlaybackUrls()
        - $stream->snapshot()
        - $stream->saveAs()
        - $stream->delete()
