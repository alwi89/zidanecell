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
		$a = mysql_query("select tgl_return, m.no_nota, m.no_nota_grosir, nama, nama_sales, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty_tukar jml_tukar_barang, qty jml_potong_nota, d.harga*qty total_potong_nota from karyawan k join return_grosir m on k.username=m.username join return_grosir_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join grosir p on m.no_nota_grosir=p.no_nota join sales s on p.kode_sales=s.kode_sales where tgl_return between '$dari' and '$sampai' order by tgl_return asc, no_nota asc");
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
		$daris = explode("/", escape($_POST['rgrdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['rgrsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select tgl_return, m.no_nota, m.no_nota_grosir, nama, nama_sales, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty_tukar jml_tukar_barang, qty jml_potong_nota, d.harga*qty total_potong_nota from karyawan k join return_grosir m on k.username=m.username join return_grosir_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode join grosir p on m.no_nota_grosir=p.no_nota join sales s on p.kode_sales=s.kode_sales where tgl_return between '$dari' and '$sampai' order by tgl_return asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_return_grosir_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['rgrdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['rgrsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_return_grosir_excel.php?dari=$dari&sampai=$sampai");
	}
	
}
?>