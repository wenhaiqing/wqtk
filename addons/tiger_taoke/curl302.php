<?php

$url = 'https://s.click.taobao.com/VzV1Zow';
$cookie_file = dirname(__FILE__) . '/my.cookie';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Host: s.click.taobao.com",
    "Connection: keep-alive",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Encoding: gzip, deflate, sdch, br",
    "Accept-Language: zh-CN,zh;q=0.8"
));
$output = curl_exec($ch);
$curlinfo = curl_getinfo($ch);
curl_close($ch);

if (isset($curlinfo["redirect_url"])) {
    $url = $curlinfo["redirect_url"];
    $cookie_file = dirname(__FILE__) . '/my.cookie';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Host: s.click.taobao.com",
        "Connection: keep-alive",
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Encoding: deflate, sdch, br",
        "Accept-Language: zh-CN,zh;q=0.8"
    ));
    $output = curl_exec($ch);
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);

    //解析页面生成新的url
    $oulurl = $url;
    $tuurl = explode('?tu=', $url);
    $url = urldecode($tuurl[1]);
    $cookie_file = dirname(__FILE__) . '/my.cookie';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Host: s.click.taobao.com",
        "Connection: keep-alive",
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Referer: " . $oulurl,
        "Accept-Encoding: deflate, sdch, br",
        "Accept-Language: zh-CN,zh;q=0.8"
    ));
    $output = curl_exec($ch);
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);
    $url = $curlinfo["redirect_url"];
    var_dump($url);
}