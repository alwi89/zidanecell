<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data';
$_POST['dari']='01/03/2016';
$_POST['sampai']='31/03/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select tgl_return, m.no_nota, nama, d.kode, tipe, nama_barang, qty from karyawan k join return_barang_masuk m on k.username=m.username join return_barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where tgl_return between '$dari' and '$sampai' order by tgl_return asc, no_nota asc");
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
		$daris = explode("/", escape($_POST['rbmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['rbmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select tgl_return, m.no_nota, nama, d.kode, tipe, nama_barang, qty from karyawan k join return_barang_masuk m on k.username=m.username join return_barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where tgl_return between '$dari' and '$sampai' order by tgl_return asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_return_barang_masuk_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['rbmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['rbmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_return_barang_masuk_excel.php?dari=$dari&sampai=$sampai");
	}
	
}
?>