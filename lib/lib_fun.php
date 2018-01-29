<?php

/**
 *
 * @param $rss RSS內容
 *
 * @return void 將RSS轉成array
 */
function parser_rss($rss)
{
    $old_xml = new SimpleXMLElement($rss,LIBXML_NOCDATA);
    $array = json_decode(json_encode($old_xml),true);


    $article = array();
    foreach ($array["channel"]["item"] as $item)
    {


        //使用文章的guid當作key
        $article[$item["guid"]] = $item;

        $article[$item["guid"]]["weibo_title"] = $array["channel"]["title"];
    }
    return $article;
}