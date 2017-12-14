<?php
include_once('C_Controller.php');
include_once('m/M_Users.php');
include_once('startup.php');


class C_Base extends C_Controller{
	protected $content;
	protected $pageTitle;
	protected static $mUser;
	protected $user;
	
	public function __construct(){
		
	}
	
	protected function onInput(){
		startup() or die('Неудалось получить конфигурационные файлы');
		self::$mUser = M_Users::Instance();
		$this->user = self::$mUser->Get();
		$this->content = '';
	}
	
	protected function onOutput(){
		$vars = array('content'=>$this->content);
		$page = $this->template('v/v_main.php', $vars);
		echo $page;
	}
	
	protected function clean($value=""){
		$value = trim($value);
		$value = strip_tags($value);
		$value= stripcslashes($value);
		$value = htmlspecialchars($value);
		return $value;
	}

	protected function checkLength($value="", $min, $max){
		if((mb_strlen($value, 'utf8') > $min) && (mb_strlen($value, 'utf8') < $max))
			return true;
	}
	
}