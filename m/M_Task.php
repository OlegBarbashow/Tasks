<?php
//
// Менеджер задач
//

class M_Task{
	
	public static $instance;
	public $msql;
	
	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new M_Task();
		}
		return self::$instance; 
	}
	
	public function __construct(){
		$this->msql = new MSQL();
	}
	
	public function all(){
		// Запрос.
		$query = "SELECT * FROM Tasks ORDER BY id_task DESC";
		
		return $this->msql->select($query);
	}

	//
	// Конкретная задача
	//
	public function get($id_task){
		$t = "SELECT * FROM Tasks WHERE id_task = %d";
		$query = sprintf($t, 
							mysql_real_escape_string($id_task));
		$result = $this->msql->select($query);
		return $result[0];
	}

	//
	// Добавить задачу
	//
	public function add($name, $email, $content, $image){
		var_dump($image);
		// Подготовка.
		$name = trim($name);
		$email = trim($email);
		$content = trim($content);

		// Проверка.
		if ($name == '')
			return false;
		
		if ($email == '')
			return false;
		
		if ($content == '')
			return false;
		
		// Запрос.
		$obj = array();
		$obj['name'] = $name;
		$obj['email'] = $email;
		$obj['content'] = $content;
		$obj['image'] = $image;
		$this->msql->insert('Tasks', $obj);
		var_dump($obj);
		return true;
	}

	//
	// Изменить задачу
	//
	public function edit($id_task, $name, $email, $content, $image, $isReady){
		$obj = array();
		$obj['name'] = $name;
		$obj['email'] = $email;
		$obj['content'] = $content;
		$obj['image'] = $image;
		$obj['isReady'] = $isReady;
		$t = "id_task='%s'";
		$where = sprintf($t, $id_task);
		$this->msql->update('Tasks', $obj, $where);
		return true;
	}

	//
	// Удалить задачу
	//
	public function delete($id_task){
		$t = "id_task='%d'";
		$where = sprintf($t, $id_task);
		$this->msql->delete('Tasks', $where);
		return true;
	}

	//
	// Короткое описание задачи
	//
	public function intro($task){	
		$len = mb_strlen($task['content'], 'utf8');
		if($len < 30){
			return $task['content'];
		}
		else{
			return (mb_substr($task['content'], 0, 60, 'utf8') . '...');
		}
	}
	
	public function setPagination($page, $sortType=null, $num = 3){
		// Переменная $num хранит число сообщений выводимых на станице  
		
		// Определяем общее число сообщений в базе данных
		$query = "SELECT COUNT(*) FROM Tasks";
		$result = mysql_query($query);  
		$posts = mysql_result($result, 0);
		
		// Находим общее число страниц  
		$total = intval(($posts - 1) / $num) + 1;  
		
		// Определяем начало сообщений для текущей страницы  
		$page = intval($page);  
		
		// Если значение $page меньше единицы или отрицательно  
		// переходим на первую страницу  
		// А если слишком большое, то переходим на последнюю  
		if(empty($page) or $page < 0) $page = 1;  
		  if($page > $total) $page = $total;
		  
		// Вычисляем начиная к какого номера  
		// следует выводить сообщения  
		$start = $page * $num - $num;
		
		// Сортировка
		switch($sortType){
			case 'nameAZ':{
				$t = "SELECT * FROM Tasks ORDER BY name LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			}
			case 'nameZA':
				$t = "SELECT * FROM Tasks ORDER BY name DESC LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			case 'mailAZ':{
				$t = "SELECT * FROM Tasks ORDER BY email LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			}
			case 'mailZA':
				$t = "SELECT * FROM Tasks ORDER BY email DESC LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			case 'notReady':{
				$t = "SELECT * FROM Tasks ORDER BY isReady LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			}
			case 'ready':
				$t = "SELECT * FROM Tasks ORDER BY isReady DESC LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
				break;
			default:
				$t = "SELECT * FROM Tasks LIMIT %d, %d";
				$query = sprintf($t, $start, $num);
		}
		
		$tasks = $this->msql->select($query);
		 
		// Проверяем нужны ли стрелки назад 
		$pervpage="";
		$nextpage="";
		
		if ($page != 1) $pervpage = '<li><a href= ./index.php?page='.($page - 1).' aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		
		// Проверяем нужны ли стрелки вперед  
		if ($page != $total) $nextpage = '<li><a href= ./index.php?page='.($page + 1).' aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		
		$p = 1;
		
		return array($tasks, $pervpage, $nextpage, $p, $total);	
	}
	
	//
	// Загрузка и проверка изображения, форматирование
	//
	public function upload_file($file){
		if($file == null){
			return false;
		}
		
		$image = new classSimpleImage();
		$image->load($file['tmp_name']);
		
		$height = $image->getHeight();
		$width = $image->getWidth();
		
		if($height > 240 && $width > 320){
			$heightDifference = $height - 240;
			$widthDifference = $width - 320;
			if($heightDifference > $widthDifference){
				$image->resizeToHeight(240);
			}
			else{
				$image->resizeToWidth(320);
			}
		}
		elseif($height > 240){
			$image->resizeToHeight(240);
		}
		elseif($width > 320){
			$image->resizeToWidth(320);
		}

		if($image->save('img/'.$file['name'])){
			return 'img/'.$file['name'];
		}
		else{
			return false;
		}
	}
}