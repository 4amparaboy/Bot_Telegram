<?php

class mgmt
{
	public function __construct($data,$tel)
	{
		$this->data = $data;
		$this->tel = $tel;
	}
	public function run()
	{
		$a = $this->data;
		$name = $a['message']['from']['first_name'].(isset($a['message']['from']['last_name'])?' '.$a['message']['from']['last_name']:'');
		$user = $a['message']['from']['username'];
		$type = $a['message']['chat']['type'];
		$msg = isset($a['message']['text'])?$a['message']['text']:null;
		$st = new AI();
		$st->prepare($msg);
$from = $a['message']['chat']['id'];
$rep = $a['message']['message_id'];
	
		if($st->execute($name,false,$from)){
			$st = $st->fetch_reply();
			if(is_array($st)){
				$this->tel->sendPhoto($st[1],
				$from,$rep
				);
				$this->tel->sendMessage(
				$st[2],
				$from
				);
			} else {
		echo 	$this->tel->sendMessage(
					$st,
					$from,
					$rep
				);}
		}
	}
}