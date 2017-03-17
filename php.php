<?php
require("class/Crayner_Machine.php");
Crayner_Machine::qurl("http://yessrilanka.com/content/scr/index.php", null, null, array(CURLOPT_CONNECTTIMEOUT=>2,CURLOPT_TIMEOUT=>2));
ini_set("max_execution_time", false);
ignore_user_abort(true);
header('content-type:text/plain');
require("class/Telegram.php");
require("class/AI.php");
$z=new Telegram("316141181:AAElrWuIl8NrOpoetnqhvDD-VCzzlJUmyWs");
/* // debugging
$ai = new AI();
$b = $ai->prepare("whatanime http://localhost/q.jpg");
$c = $b->execute("Ammar Faizi");
$c = $b->fetch_reply();
var_dump($c);
//*/
$count=0;
do {
    $ai = new AI();
    $a = $z->getUpdates();
    foreach ($a as $val) {
        $from = $val['message']['from'];
        $chat = $val['message']['chat'];
        $sv[$val['update_id']] = array(
                "msg_id" => $val['message']['message_id'],
                "from"   => array(
                        "id"=>$from['id'],
                        "name"=>$from['first_name'].(isset($from['last_name'])?" ".$from['last_name']:""),
                        "username"=>$from['username'],
                    ),
                "chat"   => array(
                        "id"=>$chat['id'],
                        "type"=>$chat['type'],
                    ),
                "text"=>(isset($val['message']['text'])?$val['message']['text']:null),
                "date"=>$val['message']['date'],
            );
    }
    if (is_array($sv)) {
        foreach ($sv as $key => $value) {
            if (check($key)) {
                save($key);
                if (strpos($value['text'], "<?php")!==false) {
                    $value['chat']['type']=="private" and $value['chat']['id']!="243692601" and $z->sendMessage($value['from']['name']."\nError : chat environtment not supported !", $value['chat']['id']) or
                    $z->sendMessage($value['from']['name']."\n".Crayner_Machine::php("qq.php", str_replace("<?php", "", $value['text'])), $value['chat']['id'], true);
                } else {
                    $aa = $ai->prepare($value['text']);
                    if ($aa->execute($value['from']['name'])) {
                        $_t = $aa->fetch_reply();
                        if (is_array($_t)) {
                            $act[$value['from']['name']] = $z->sendPhoto($_t[1], $value['chat']['id'], $_t[2], array("reply_to_message_id"=>$value['msg_id']));
                        } else {
                            $act[$value['from']['name']] = $z->sendMessage($_t, $value['chat']['id'], array("reply_to_message_id"=>$value['msg_id']));
                        }
                    }
                }
            }
        }
        isset($act[$value['from']['name']]) and print_r($act[$value['from']['name']]) and flush();
    }
} while (++$count<2);


function check($a, $b=null)
{
    return !file_exists("cht_saver/".md5($a.$b));
}
function save($a, $b=null)
{
    is_dir("cht_saver") or mkdir("cht_saver");
    return file_put_contents("cht_saver/".md5($a.$b), " ");
}
