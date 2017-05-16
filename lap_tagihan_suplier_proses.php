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
		$a = mysql_query("select no_nota, nama_suplier, no_tlp, total, dibayar, kekurangan, tgl_tempo from barang_masuk g join suplier s on g.kode_suplier=s.kode_suplier where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai'");
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
		$daris = explode("/", escape($_POST['tpdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tpsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$a = mysql_query("select no_nota, nama_suplier, no_tlp, total, dibayar, kekurangan, tgl_tempo from barang_masuk g join suplier s on g.kode_suplier=s.kode_suplier where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_tagihan_suplier_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['tpdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tpsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_tagihan_suplier_excel.php?dari=$dari&sampai=$sampai");
	}
	
}
?>