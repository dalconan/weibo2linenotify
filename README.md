# weibo2linenotify
讀取微博的RSS，透過LineNotify服務追蹤

# 需求
PHP 7

# 事前準備
1.想要追蹤的微博 uid

2.申請Line Notify的client_id和client_secret

3.自己LINE帳號的access_token

4.一台永不關機的電腦

# 使用方法
將client_id和client_secret以及access_token填入，然後指定好rss要存放的路徑，這邊建議使用絕對路徑，除非可以確保執行時的所在位置，
如果資料夾不存在請先建立。(本範例是將rss資料夾放在專案資料夾下）
```
//設定接收資訊
$client_id      = "a0465ge347jIYd";
$client_secret  = "i4dETpxwUjLCTRPL53lwfEiaPAPr3P2aHaKc0dqMdJc";
$token          = "6ny9GwoWFbnmQBRWqdgYHhYeQiEnxE96MtmQmKZRhtG";
....

$rss_path = "/Users/coder/Documents/weibo2linenotify/rss/";
```

如果執行的環境是windows系統，請注意路徑格式。

設置完成後在終端機執行

`php /Users/coder/Documents/weibo2linenotify/getweiboxml.php 微博uid`

第一次執行時只會將rss檔案建立，之後只要設定排程每隔一段時間執行以上命令，就可以達到持續追蹤的效果。

# 製作原理
微博其實沒有提供RSS的機制，所以是透過第三方服務完成，[DIYgod/Weibo2RSS](https://github.com/DIYgod/Weibo2RSS) 所提供的方法所完成，將取得的RSS
和前一次取得的比較，然後將新增的項目透過LINE notify的服務送到指定的LINE帳號。

# 附註
必須開啟PHP的cURL和SSL連線，如果是使用xampp這種快速架站包的話基本上都有自動設定好了
