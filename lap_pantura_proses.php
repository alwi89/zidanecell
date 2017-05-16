<?php
require_once("koneksi.php");
session_start();
$id_cabang = $_SESSION['cbg'];
/*
$_POST['aksi']='data';
$_POST['dari']='01/04/2016';
$_POST['sampai']='31/04/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select tgl_keluar, m.no_nota, nama_cabang, nama, d.kode, tipe, nama_barang, d.harga_modal, qty, d.harga_modal*qty sub_total from karyawan k join kirim_cabang m on k.username=m.username join kirim_cabang_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join cabang c on m.kode_cabang=c.kode_cabang where m.id_cabang='$id_cabang' and c.jenis='pantura' and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['panturadari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['panturasampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select tgl_keluar, m.no_nota, nama_cabang, nama, d.kode, tipe, nama_barang, d.harga_modal, qty, d.harga_modal*qty sub_total from karyawan k join kirim_cabang m on k.username=m.username join kirim_cabang_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join cabang c on m.kode_cabang=c.kode_cabang where m.id_cabang='$id_cabang' and c.jenis='pantura' and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_pantura_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['panturadari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['panturasampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_pantura_excel.php?dari=$dari&sampai=$sampai");
	}
	
}
?>