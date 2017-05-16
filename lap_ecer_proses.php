<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data cabang';
$_POST['dari']='21/05/2016';
$_POST['sampai']='26/11/2016';
$_POST['cabang']='SEMUA';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$cabang = $_POST['cabang'];
		$a = mysql_query("select tgl_keluar, m.no_nota, nama, d.kode, tipe, nama_barang, d.harga_modal, d.harga harga_jual, qty, d.harga*qty sub_total from karyawan k join ecer m on k.username=m.username join ecer_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.id_cabang='$cabang' and tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = array('tgl_keluar' => $b['tgl_keluar'], 'no_nota' => $b['no_nota'], 'nama' => $b['nama'], 'kode' => $b['kode'], 'tipe' => trim($b['tipe']), 'nama_barang' => $b['nama_barang'], 'harga_modal' => $b['harga_modal'], 'harga_jual' => $b['harga_jual'], 'qty' => $b['qty'], 'sub_total' => $b['sub_total']);
			}
			
		}
//		echo json_encode(array_merge(json_decode($data, true),json_decode($data_kekurangan, true)));
		echo json_encode($data);
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['ecdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['ecsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$cabang = $_POST['lapeccab'];
		if($cabang=='SEMUA'){
			$x = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where tgl_keluar between '$dari' and '$sampai')");
			$nama_cabang = "semua";
		}else{
			$x = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where id_cabang='$cabang' and tgl_keluar between '$dari' and '$sampai')");
			$m = mysql_query("select nama_cabang from cabang where kode_cabang='$cabang'");
			$n = mysql_fetch_array($m);
			$nama_cabang = $n['nama_cabang'];
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_ecer_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['ecdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['ecsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$cabang = $_POST['lapeccab'];//
		header("location:lap_ecer_excel.php?dari=$dari&sampai=$sampai&cabang=".urlencode($cabang));
	}else if($_POST['aksi']=='data cabang'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$cabang = $_POST['cabang'];
		if($cabang=='SEMUA'){
			$a = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where tgl_keluar between '$dari' and '$sampai')");
		}else{
			$a = mysql_query("select * from cabang where kode_cabang in(select id_cabang from ecer where id_cabang='$cabang' and tgl_keluar between '$dari' and '$sampai')");
		}
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}
	
}
?>