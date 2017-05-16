<?php
require_once("koneksi.php");
session_start();
if(isset($_POST['aksi_akun'])){
	if($_POST['aksi_akun']=='edit'){
		$kode_lama_karyawan = escape($_POST['kode_lama_karyawan']);
		$username = escape($_POST['username']);
		$nama_karyawan = escape($_POST['nama_karyawan']);
		$no_tlp = escape($_POST['no_tlp_karyawan']);
		$pin_bb = escape($_POST['pin_bb_karyawan']);
		$password = escape($_POST['password']);
		$a = mysql_query("update karyawan set username='$username', nama='$nama_karyawan', no_telp='$no_tlp', pin_bb='$pin_bb', password='$password' where username='$kode_lama_karyawan'");
		if($a){
			$_SESSION['usrzdncl'] = $username;
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit akun');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit akun');
		}
		echo json_encode($data);
	}else if($_POST['aksi_akun']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['aksi_akun']=='data akun'){
		$kode = $_SESSION['usrzdncl'];
		$a = mysql_query("select * from karyawan k left join cabang c on k.id_cabang=c.kode_cabang where username='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}
}
?>