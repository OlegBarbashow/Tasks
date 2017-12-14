<?php
	abstract class C_Controller{
		public function __construct(){
			
		}
		
		protected function onInput(){
			
		}
		
		protected function onOutput(){
			
		}
		
		public function request(){
			$this->onInput();
			$this->onOutput();
		}
		
		protected function isPost(){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				return true;
			}
		}
		
		protected function isGet(){
			if($_SERVER['REQUEST_METHOD'] == 'GET'){
				return true;
			}
		}
		
		protected function template($fileName, $vars = array()){
			foreach($vars as $k=>$v){
				$$k = $v;
			}
			
			ob_start();
			include_once($fileName);
			return ob_get_clean();
		}
		
	}