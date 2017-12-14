<?php
class MSQL{
	
	private static $instance;
	
	public function __construct(){}
	
	public static function Instance(){
		if(self::$instance == null){
			self::$instance = new MSQL();
		}
		return self::$instance;
	}
	
	//
	// Выборка строк
	//
	public function select($query){
		$result = mysql_query($query);
		
		if(!$result){
			die(mysql_error());
		}
		
		$arr = array();
		$num = mysql_num_rows($result);
		for($i = 0; $i < $num; $i++){
			$row = mysql_fetch_assoc($result);
			$arr[] = $row;
		}
		return $arr;
	}
	
	//
	// Вставка строки
	//
	public function insert($table, $object){
		$columns = array();
		$values = array();
		
		foreach($object as $key=>$value){
			$key = mysql_real_escape_string($key.'');
			$columns[] = $key;
			
			if($value === null){
				$values[] = 'NULL';
			}
			else{
				$value = mysql_real_escape_string($value.'');
				$values[] = "'$value'";
			}
			
			
			$columns_s = implode(',', $columns);
			$values_s = implode(',', $values);
		}
		
		$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
		
		
		$result = mysql_query($query);
		if(!$result){
			die(mysql_error());
		}
		
		return mysql_insert_id();
	}
	
	//
	// Функция обновления строки
	//
	public function update($table, $object, $where){
		
		$sets = array();
		
		foreach($object as $key => $value){
			$key = mysql_real_escape_string($key.'');
			
			if($value === null){
				$sets[] = "$key = NULL";
			}
			else{
				$value = mysql_real_escape_string($value.'');
				$sets[] = "$key = '$value'";
			}
		}	
		$sets_s = implode(',', $sets);
		$query = "UPDATE $table SET $sets_s WHERE $where";
		$result = mysql_query($query);
		
		if(!$result){
			die(mysql_error());
		}
		
		return mysql_affected_rows();
		
	}
	
	//
	// Удаление строк
	//
	public function delete($table, $where){
		$query = "DELETE FROM $table WHERE $where";
		$result = mysql_query($query);
		
		if(!$result){
			die(mysql_error());
		}
		return mysql_affected_rows();
	}
}