<?php

require(join(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'lib', 'Pili.php')));

$ak = "7O7hf7Ld1RrC_fpZdFvU8aCgOPuhw2K4eapYOdII";
$sk = "6Rq7rMSUHHqOgo0DJjh15tHsGUBEH9QhWqqyj4ka";
$hubName = "PiliSDKTest";

//创建hub
echo "================Create hub\n";
$mac = new Qiniu\Mac($ak, $sk);
$client = new Pili\Client($mac);
$hub = $client->hub($hubName);
print_r($hub);
//获取stream
echo "================Get stream\n";
$streamKey = "php-sdk-test" . time();
$stream = $hub->stream($streamKey);
print_r($stream);

try {
    //创建stream
    echo "================Create stream\n";
    $resp = $hub->create($streamKey);
    print_r($resp);
    //获取stream info
    echo "================Get stream info\n";
    $resp = $stream->info();
    print_r($resp);
    //列出所有流
    echo "================List streams\n";
    $resp = $hub->listStreams("php-sdk-test", 1, "");
    print_r($resp);
    //列出正在直播的流
    echo "================List live streams\n";
    $resp = $hub->listLiveStreams("php-sdk-test", 1, "");
    print_r($resp);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

try {
    echo "================Get liveStatus:\n";
    $status = $stream->liveStatus();
    print_r($status);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

try {
    //启用流
    echo "================Enable stream:\n";
    $stream->enable();
    $status = $stream->liveStatus();
    echo "liveStatus:\n";
    print_r($status);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

try {
    //禁用流
    echo "================Disable stream:\n";
    $stream->disable();
    $status = $stream->liveStatus();
    echo "liveStatus:\n";
    print_r($status);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

try {
    //保存直播数据
    echo "================Save stream:\n";
    $fname = $stream->save(1463217523, 1463303923);
    print_r($fname);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

try {
    //查询推流历史
    echo "================Get stream history record:\n";
    $records = $stream->historyActivity(1463217523, 1463303923);
    print_r($records);
} catch (\Exception $e) {
    echo "Error:", $e, "\n";
}

//RTMP 推流地址
$url = Pili\RTMPPublishURL("publish-rtmp.test.com", $hubName, $streamKey, 3600, $ak, $sk);
echo $url, "\n";
//RTMP 直播放址
$url = Pili\RTMPPlayURL("live-rtmp.test.com", $hubName, $streamKey);
echo $url, "\n";
//HLS 直播地址
$url = Pili\HLSPlayURL("live-hls.test.com", $hubName, $streamKey);
echo $url, "\n";
//HDL 直播地址
$url = Pili\HDLPlayURL("live-hdl.test.com", $hubName, $streamKey);
echo $url, "\n";
//截图直播地址
$url = Pili\SnapshotPlayURL("live-snapshot.test.com", $hubName, $streamKey);
echo $url, "\n";

