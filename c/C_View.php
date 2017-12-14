<?php
include_once('C_Base.php');

class C_View extends C_Base{
	protected $task;
	public $editing='';
	public function __construct(){
		$this->pageTitle = "Просмотр задачи";
		
	}
	
	protected function onInput(){
		parent::onInput();
		
		$mTask = M_Task::getInstance();
		if(!empty($_GET['id'])){
			$id_task = $_GET['id'];
		} 
		$this->task = $mTask->get($id_task);
		
		// Очистка старых сессий
		self::$mUser->ClearSessions();
		
		// Если пользователь зарегистрирован - создаем кнопку редактирования
		if ($this->user != null){
			$this->editing = '<a href="index.php?c=edit&id='.$this->task["id_task"].'" class="btn btn-primary btn-lg active" role="button">Редактировать</a>';
		}
	}
	
	protected function onOutput(){
		$vars = array('task'=>$this->task, 'pageTitle'=>$this->pageTitle, 'editing'=>$this->editing);
		$this->content = $this->template('v/v_view.php', $vars);
		parent::onOutput();
	}
}