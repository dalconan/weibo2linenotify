<?php


require_once 'class/line_notify_tool.php';
require_once 'lib/lib_fun.php';

//設定接收資訊
$client_id      = "";
$client_secret  = "";
$token          = "";

$uid = $argv[1];   //使用此uid取得XML

/**
 * RSS檔案路徑，使用絕對路徑，如果使用相對路敬請確保執行檔案時所在的位置。
 */
$rss_path = "";
$rss_file_name =  $rss_path . $uid .".rss";

$ch = curl_init ();


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ( $ch, CURLOPT_URL, "https://api.prprpr.me/weibo/rss/".$uid );

$newrss = curl_exec ( $ch );

curl_close ( $ch );


if(!file_exists($rss_file_name))
{
    //如果檔案不存在
    //產生檔案
    file_put_contents($rss_file_name ,$newrss);

}
else
{
    //已存在，做比較;
    $oldrss = file_get_contents($rss_file_name);

    $old_xml = parser_rss($oldrss);


    $new_xml = parser_rss($newrss);

    $new_article = array_diff_key($new_xml,$old_xml);

    $line_notift = new line_notify_tool($client_id,$client_secret,$token);
    foreach ($new_article as $notify_message)
    {
        $message = "";
        $message .= $notify_message["weibo_title"]." 發佈了一篇新訊息\n ";
        $message .= $notify_message["title"]." \n";
        $message .= $notify_message["link"]." \n";
        $line_notift->send_notify($message);
    }
    file_put_contents($rss_file_name ,$newrss);
}









