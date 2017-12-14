<?php
include_once('C_Base.php');

class C_Edit extends C_Base{
	protected $task;
	protected $errors = array();
	
	public function __construct(){
		$this->pageTitle = "Просмотр задачи";
	}
	
	protected function onInput(){
		parent::onInput();
		// Очистка старых сессий
		self::$mUser->ClearSessions();
		

		// Если пользователь не зарегистрирован - отправляем на страницу регистрации.
		if ($this->user == null)
		{
			header("Location: login.php");
			die();
		}
		$mTask = M_Task::getInstance();
		if($this->isPost()){
			if($_POST['button'] == 'Сохранить'){
				$name = $_POST['name'];
				$email = $_POST['email'];
				$content = $_POST['content'];
				$isReady = $_POST['isReady'];
				$id_task = $_POST['id_task'];

				if(isset($_FILES['file']) && !empty($_FILES['file']['name'])){
					$imgDir = $mTask->upload_file($_FILES['file']);
				}
				elseif(!empty($_POST['image'])){
					$imgDir = $_POST['image'];
				}
				else{
					$imgDir=null;
				}
				
				$name = $this->clean($name);
				$email = $this->clean($email);
				$content = $this->clean($content);
				
				if(!$this->checkLength($name, 2, 255) || !$this->checkLength($content, 4, 65535)){
					$this->errors[]="Длинна имени > 2 символов;<br>Контет > 4 символов;<br>";
				}
				
				if(true != filter_var($email, FILTER_VALIDATE_EMAIL)){
					$this->errors[] = "E-mail указан верно.";
				}
				
				if(empty($this->errors) && $mTask->edit($id_task, $name, $email, $content, $imgDir, $isReady)){
					header("Location:index.php?page=1");
					die();
				}
				
			}
			elseif($_POST['button'] == 'Удалить'){
				$id_task = $_POST['id_task'];
				if($mMtask->delete($id_task)){
					header('Location: index.php?page=1');
					die();
				}
			}
		}
				
		$id_task = $_GET['id'];
		$this->task = $mTask->get($id_task);
	}
	
	protected function onOutput(){
		$vars = array('task'=>$this->task, 'pageTitle'=>$this->pageTitle, 'errors'=>$this->errors); //'error'=>$this->error
		$this->content = $this->template('v/v_edit.php', $vars);
		parent::onOutput();
	}
}