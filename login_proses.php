<?php
require_once("koneksi.php");
$username = escape($_POST['username']);
$pwd = escape($_POST['password']);
$a = mysql_query("select * from karyawan where username='$username' and password='$pwd' and status='aktif'");
$cek = mysql_num_rows($a);
if($cek==0){
	setcookie("msg", "username atau password salah", time()+10);
	header("location:login.php");
}else{
	$b = mysql_fetch_array($a);
	if($b['id_cabang']==NULL){
		setcookie("msg", "karyawan tidak terdaftar dicabang manapun", time()+10);
		header("location:login.php");
	}else{
		session_start();
		$x = mysql_query("select jenis from cabang where kode_cabang='$b[id_cabang]'");
		$y = mysql_fetch_array($x);
		$_SESSION['jns'] = $y['jenis'];
		$_SESSION['cbg'] = $b['id_cabang'];
		$_SESSION['usrzdncl'] = $b['username'];
		$_SESSION['lvlusrzdncl'] = $b['level'];
		header("location:index.php");
	}
}
?>