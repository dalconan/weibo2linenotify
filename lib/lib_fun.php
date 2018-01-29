<?php
require_once 'simple_html_dom.php';

/**
 *
 * @param $rss RSS內容
 *
 * @return array 將RSS轉成array
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


/**
 * 將頁面資訊讀取成json
 *
 * @param $html 頁面
 * @return string
 */
function parser2json($html)
{
    //文章清單
    $articles = array();
    $dom = str_get_html($html);
    $items = $dom->find(".wgtCell");
    $weibo_name = $dom->find('.userNm',0)->plaintext." 的微博";

    foreach ($items as $item)
    {
        //單一文章資訊
        $article = array();
        $article["weibo_title"] =  $weibo_name;

        $content = $item->find(".wgtCell_txt",0)->plaintext;

        //取得標題
        $article["title"] =   trim($content);
        if ( strlen($article["title"]) > 24 )
        {
            $article["title"] = mb_substr($article["title"],0,24);
        }

        $article["description"] = preg_replace("/thumbnail/","large",trim($content));

        //取得時間
        $article["pubDate"] = weibo_getdate($item->find('.link_d',0)->plaintext);

        $link = $item->find('.wgtCell_tm a',0)->href;
        $article["guid"] = $link ;
        $article["link"] = $link ;

        //整理到$articles
        $articles[$article["guid"]] = $article;

    }


    $json = json_encode($articles);

    return $json;
}


function weibo_getdate($html)
{
    $now = date("Y-m-d H:i");
    if (preg_match("/(\d+)分钟前/", $html,$matches))
    {
        return date('Y-m-d H:i:', strtotime('-2 '.$matches[1].'hour', $now));
    }
    else if (preg_match("/今天 (\d+):(\d+)/", $html,$matches))
    {
        return $now = date("Y-m-d ").$matches[1].":".$matches[2];

    }
    else if (preg_match("/(\d+)月(\d+)日 (\d+):(\d+)/", $html,$matches))
    {
        return $now = date("Y-").$matches[1]."-".$matches[2]." ".$matches[3].":".$matches[4];
    }
    return html;
}