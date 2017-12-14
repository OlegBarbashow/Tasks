<?php

function startup()
{
	// Настройки подключения к БД.
	define('HOSTNAME', 'localhost'); 
	define('USERNAME', ''); 
	define('PASSWORD', '');
	define('DATABASE', '');
	
	// Языковая настройка.
	setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');
	
	// Настроука временной зоны
	date_default_timezone_set('Europe/Kiev');
	
	// Подключение к БД.
	mysql_connect(HOSTNAME, USERNAME, PASSWORD) or die('No connect with server'); 
	mysql_set_charset('utf8');
	mysql_select_db(DATABASE) or die('No data base');
	header('Content-type: text/html; charset=utf8');
	
	// Открытие сессии.
	session_start();
	return true;	
}