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
		echo $msg;
		if($st->execute($name)){
			echo 1;
			$st = $st->fetch_reply();
		echo 	$this->tel->sendMessage(
					$st,
					$a['message']['chat']['id'],
					$a['message']['message_id']
			);
		}
	}
}