<?php
include_once('C_Base.php');

class C_Editor extends C_Base{
	protected $articles;
	
	public function __construct(){
		$this->menuActiv = 'editor';
	}
	
	protected function onInput(){
		parent::onInput();
		// Очистка старых сессий.
		self::$mUser->ClearSessions();
		
		
		
		//Если нет регистрации - отправляем назад
		if($this->user == null){
			header('Location: index.php?c=login');
			die();
		}
		
		$mArticles = M_Articles::getInstance();
		$this->articles = $mArticles->all();
	}
	
	protected function onOutput(){
		$vars = array('articles'=>$this->articles);
		$this->content = $this->template('v/v_editor.php', $vars);
		parent::onOutput();
	}
}