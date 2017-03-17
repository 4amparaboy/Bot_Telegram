<?php
class Telegram extends Crayner_Machine
{
    public function __construct($token)
    {
        $this->q="https://api.telegram.org/bot".$token."/";
    }
    public function clear()
    {
        return $this->qurl($this->q."getUpdates?offset=-1");
    }
    public function getUpdates()
    {
        $a=$this->qurl($this->q."getUpdates?limit=30", null, array("offset"=>100));
        //file_put_contents("aa",$a);
        //$a=file_get_contents("aa");
        $a=json_decode($a, true);
        $a=$a['result'];
        $rt=$a;
        return $rt;
    }
    public function sendMessage($text, $to, $q=null)
    {
        $post = array("chat_id"=>$to,"text"=>$text);
        if (is_array($q)) {
            $post = array_merge($post, $q);
        }
        return $this->qurl($this->q."sendMessage", null, $post);
    }
    public function sendPhoto($photo, $to, $capt=null, $q=null)
    {
        if (file_exists($photo)) {
            if (function_exists('curl_file_create')) {
                $cFile = curl_file_create($photo);
            } else {
                $cFile = '@'.realpath($photo);
            }
        } else {
            $cFile=$photo;
        }
        $post = array("chat_id"=>$to,"photo"=>$cFile,"caption"=>$capt);
        if (is_array($q)) {
            $post = array_merge($post, $q);
        }
        return $this->qurl($this->q."sendPhoto", null, $post);
    }
}
