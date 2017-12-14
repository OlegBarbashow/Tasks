<?php
class C_Authorization extends C_Base{
	public function __construct(){
		$this->pageTitle = "<h2>Авторизация администратора</h2>";
	}
	
	protected function onInput(){
		parent::onInput();
		
		// Очистка старых сессий
		self::$mUser->ClearSessions();
		
		// Выход
		if($this->isGet()){
			self::$mUser->Logout();
		}

		// Обработка отправки формы
		if($this->isPost()){
			var_dump($_POST);
			if(self::$mUser->Login($_POST['login'], 
							   $_POST['password'], 
							   isset($_POST['remember']))){
								   header('Location:index.php');
								   die();
							   }
		}
	}
	
	protected function onOutput(){
		$vars = array('pageTitle'=>$this->pageTitle);
		$this->content = $this->template('v/v_login.php', $vars);
		parent::onOutput();
	}
}