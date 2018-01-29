<?php


require_once 'class/line_notify_tool.php';
require_once 'lib/lib_fun.php';

//設定接收資訊
$client_id      = "";
$client_secret  = "";
$token          = "";

$uid = $argv[1];   //使用此uid取得資料

/**
 * json檔案路徑，使用絕對路徑，如果使用相對路敬請確保執行檔案時所在的位置。
 */
$json_path = "";
$json_file_name =  $json_path . $uid .".json";

$ch = curl_init ();


curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ( $ch, CURLOPT_URL, "http://service.weibo.com/widget/widget_blog.php?uid=".$uid );

$html = curl_exec ( $ch );
$new_json = parser2json($html);

curl_close ( $ch );



if(!file_exists($json_file_name))
{
    //如果檔案不存在
    //產生檔案
    //產生檔案
    file_put_contents($json_file_name ,$new_json);

}
else
{
    //已存在，做比較;
    $old_json = file_get_contents($json_file_name);

    $old_data = json_decode($old_json,TRUE);


    $new_data = json_decode($new_json,TRUE);

    $new_article = array_diff_key($new_data,$old_data);

    if( count($new_article) > 0 )
    {
        $line_notify = new line_notify_tool($client_id, $client_secret, $token);
        foreach ($new_article as $notify_message)
        {
            $message = "";
            $message .= $notify_message["weibo_title"] . " 發佈了一篇新訊息\n ";
            $message .= $notify_message["title"] . " \n";
            $message .= $notify_message["link"] . " \n";
            $line_notify->send_notify($message);
        }
        file_put_contents($json_file_name, $new_json);
    }
}


