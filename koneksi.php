<?php
$host = 'localhost';
$usr = "root";
$pwd = '';
$db = "zidane";
mysql_connect($host, $usr, $pwd) or die('gagal koneksi server');
mysql_select_db($db) or die('database error');
function escape($string){
	return mysql_real_escape_string($string);
}
function toIdr($number){
	return number_format($number, 2);	
}
?>