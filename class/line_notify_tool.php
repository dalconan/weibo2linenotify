<?php

class   line_notify_tool
{
    private $API_URL = "https://notify-api.line.me/api/notify";
    /**
     * ＠var Client ID
     */
    private $app_id;
    /**
     * @var Client Secret
     */
    private $app_secret;

    /**
     * @var 要接收訊息的帳號的access token
     */
    private $access_token;

    public function __construct($appid,$appsecret,$token)
    {
        $this->app_id = $appid;
        $this->app_secret = $appsecret;
        $this->access_token = $token;
    }


    /**
     * @param $message 要傳送的訊息
     */
    public function send_notify($message)
    {

        $Authorization = 'Bearer ' . $this->access_token;

        $post_data = array(
            "message" => $message,
        );

        $ch = curl_init ();


        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
            'Authorization:  ' . $Authorization,
        ) );

        curl_setopt ( $ch, CURLOPT_POST, count ( $post_data ) );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ( $ch, CURLOPT_URL, $this->API_URL );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( $post_data ) );

        curl_exec ( $ch );

        curl_close ( $ch );
    }
}