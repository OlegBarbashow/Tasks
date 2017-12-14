<?php
include_once('C_Base.php');


class C_Index extends C_Base{
	protected $tasks;
	protected $pervpage;
	protected $nextpage;
	protected $p;
	protected $total;
	
	public function __construct(){
		$this->pageTitle = "Добавить или просмотреть задачу";
	}
	
	protected function onInput(){
		parent::onInput();
		$mTasks = M_Task::getInstance();
		if(empty($_GET['page'])){
			$_GET['page'] = 1;
		}
		
		if(empty($_GET['s'])){
			$_GET['s']=null;
		}
		
		list($tasks, $pervpage, $nextpage, $p, $total) = $mTasks->setPagination($_GET['page'], $_GET['s']);
		$this->tasks = $tasks;
		$this->pervpage = $pervpage;
		$this->nextpage = $nextpage;
		$this->p = $p;
		$this->total = $total;
		$length = count($this->tasks);
		
		for($i = 0; $i < $length; $i++){
			$this->tasks[$i]['intro'] = $mTasks->intro($this->tasks[$i]);
		}
	}
	
	protected function onOutput(){
		$vars = array('tasks'=>$this->tasks, 'pageTitle'=>$this->pageTitle,
		'pervpage'=>$this->pervpage, 'nextpage'=>$this->nextpage, 'p'=>$this->p, 'total'=>$this->total);
		
		$this->content = $this->template(__DIR__.'/../v/v_index.php', $vars);
		parent::onOutput();
	}
}