<?php
include_once('C_Base.php');

class C_New extends C_Base{
	
	protected $task;
	protected $errors = array();
	
	public function __construct(){
		$this->pageTitle = "Новая задача";
	}
	
	protected function onInput(){
		parent::onInput();
		
		$mTask = M_Task::getInstance();
		if($this->isPost()){
			$name = $_POST['name'];
			$email = $_POST['email'];
			$content = $_POST['content'];
			
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
			if(empty($this->errors)){
				
				if($mTask->add($name, $email, $content, $imgDir)){
					header("Location:index.php?page=1");
					die();
				}
			}
		}
	}
	
	protected function onOutput(){
		$vars = array('pageTitle'=>$this->pageTitle, "errors"=>$this->errors);
		$this->content = $this->template('v/v_new.php', $vars);
		parent::onOutput();
	}
}