<?php

class M_Users{
	
	private static $instance;
	private static $msql;
	private $sid;
	private $uid;
	
	public function __construct(){
		self::$msql = MSQL::Instance();
		$this->sid = null;
		$this->uid = null;
	}
	
	public static function Instance(){
		if(self::$instance == null){
			self::$instance = new M_Users();
		}
		return self::$instance;
	}
	
	//
	// Очистка неиспользуемых сессий
	//
	public function ClearSessions(){
		$min = date('Y-m-d H:i:s', time() - 60 * 20);
		$t = "time_last < '%s'";
		$where = sprintf($t, mysql_real_escape_string($min));
		self::$msql->delete('sessions', $where);	
	}
	
	//
	// Авторизация
	// $login		- логин
	// $password	- пароль
	// $remember	- нужно ли запоминать в куках
	// результат true или false
	//
	public function Login($login, $password, $remember = true){
		// Вытаскиваем пользователя из БД
		$user = $this->GetByLogin($login);
		
		if($user == null){
			return false;
		}
		
		// Проверяем пароль
		if($user['password'] != md5($password)){
			return false;
		}
		
		$id_user = $user['id_user'];
		// Запоминаем имя и md5(пароль) в куках
		if($remember){
			$period = time() + 60 * 60 * 24 * 10;
			setcookie('login', $login, $period);
			setcookie('password', md5($password), $period);
		}
		
		$this->sid = $this->OpenSession($id_user);
		
		return true;
	}
	
	//
	// Выход
	//
	public function Logout(){
		setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);
		$this->sid = null;
		$this->uid = null;
	}
	
	//
	// Получаем пользователя по логину
	//
	public function GetByLogin($login){
		$t = "SELECT * FROM users WHERE login='%s'";
		$query = sprintf($t, mysql_real_escape_string($login));
		$result = self::$msql->select($query);
		if(isset($result[0])){
			return $result[0];
		}
		
		return null;
	}
	
	//
	// Получение пользователя
	// id_user	- если не указан то брать текущего
	// результат- объект пользователя
	//
	public function Get($id_user = null){
		
		if($id_user == null){
			$id_user = $this->GetUid();
			
			if($id_user == null){
				return null;
			}
		}
		// Возвращение пользователя по id_user
		$t = "SELECT * FROM users WHERE id_user = '%d'";
		$query = sprintf($t, $id_user);
		$result = self::$msql->select($query);
		if(isset($result[0])){
			return $result[0];
		}
		else{
			return null; 
		}
		
	}
	
	//
	// Получение id текущего пользователя
	// результат	- uid
	//
	public function GetUid(){
		// Проверка кеша
		if($this->uid != null){
			return $this->uid;
		}
		
		// Берем по текущей сесии
		$sid = $this->GetSid();
		
		if($sid == null){
			return null;
		}
		
		$t = "SELECT id_user FROM sessions WHERE sid ='%s'";
		$query = sprintf($t, mysql_real_escape_string($sid));
		$result = self::$msql->select($query);
		if(isset($result[0])){
			return $result[0];
			var_dump('3');
		}
		return null;
		var_dump('4');
	}
	
	//
	// Функция возвращает идентификатор текущей сессии
	// результат	- SID
	//
	private function GetSid(){
		$sid = null;
		$user = null;
		// Проверка кеша
		if($this->sid != null){
			return $this->sid;
		}
		
		// Ищем sid в сесии
		if(isset($_SESSION['sid'])){
			$sid = $_SESSION['sid'];
		}
		
		
		// Если нашли, попробуем обновить time_last в базе
		// Заодно и проверим есть ли сесия там
		if ($sid != null){
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s'); 			
			$t = "sid = '%s'";
			$where = sprintf($t, mysql_real_escape_string($sid));
			$affected_rows = self::$msql->Update('sessions', $session, $where);

			if ($affected_rows == 0){
				$t = "SELECT count(*) FROM sessions WHERE sid = '%s'";		
				$query = sprintf($t, mysql_real_escape_string($sid));
				$result = self::$msql->Select($query);
		
				if ($result[0]['count(*)'] == 0)
					$sid = null;			
			}			
		}		
			
		// Нет сесии? Ищем логин и md5(пароль) в куках
		// Т.е. пробуем переподключиться
		
		if($sid==null && isset($_COOKIE['login'])){
			$user = $this->GetByLogin($_COOKIE['login']);
		}
		
		if($user != null && $user['password'] == $_COOKIE['password']){
			$sid = $this->OpenSession($user['id_user']);
		}
		
		// Запоминаем имя в кеш
		if($sid != null){
			$this->sid = $sid;
		}

		// Возвращаем наконец sid
		return $sid;
	}
	
	private function OpenSession($id_user){
		// Генерируем SID
		$sid = $this->GenerateStr(10);
		
		$now = date('Y-m-d H:i:s');
		
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;
		
		self::$msql->insert('sessions', $session);
		
		$_SESSION['sid'] = $sid;
		
		return $sid;
	}
	
	private function GenerateStr($length = 10){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789';
		$clen = strlen($chars) - 1;
		$code = "";
		
		while(strlen($code) < $length){
			$code .= $chars[mt_rand(0, $clen)];
		}
		
		return $code;
	}
}