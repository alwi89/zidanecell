<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data';
$_POST['dari']='21/05/2016';
$_POST['sampai']='21/05/2016';
$_POST['suplier']='2';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$suplier = $_POST['suplier'];
		$a = mysql_query("select tgl_masuk, m.no_nota, nama, d.kode, tipe, nama_barang, harga, qty, harga*qty sub_total from karyawan k join barang_masuk m on k.username=m.username join barang_masuk_detail d on m.no_nota=d.no_nota join barang b on d.kode=b.kode where m.kode_suplier='$suplier' and tgl_masuk between '$dari' and '$sampai' order by tgl_masuk asc, no_nota asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$qKekurangan = mysql_query("select sum(kekurangan) kekurangan, sum(dibayar) dibayar from barang_masuk where kode_suplier='$suplier' and tgl_masuk between '$dari' and '$sampai' group by kode_suplier");
				$jml_kekurangan = mysql_num_rows($qKekurangan);
				if($jml_kekurangan==0){
					$dibayar = 'data tidak ditemukan';
					$kekurangan = 'data tidak ditemukan';
				}else{
					$hKekurangan = mysql_fetch_array($qKekurangan);
					$dibayar = $hKekurangan['dibayar'];
					$kekurangan = $hKekurangan['kekurangan'];
				}
				$data[] = array('tgl_masuk' => $b['tgl_masuk'], 'no_nota' => $b['no_nota'], 'nama' => $b['nama'], 'kode' => $b['kode'], 'tipe' => $b['tipe'], 'nama_barang' => $b['nama_barang'], 'harga' => $b['harga'], 'qty' => $b['qty'], 'sub_total' => $b['sub_total'], 'dibayar' => $dibayar, 'kekurangan' => $kekurangan);
			}
			
		}
//		echo json_encode(array_merge(json_decode($data, true),json_decode($data_kekurangan, true)));
		echo json_encode($data);
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['bmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['bmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$suplier = $_POST['lapbmsup'];
		if($suplier=='SEMUA'){
			$x = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where tgl_masuk between '$dari' and '$sampai')");
			$nama_suplier = "semua";
		}else{
			$x = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where kode_suplier='$suplier' and tgl_masuk between '$dari' and '$sampai')");
			$m = mysql_query("select nama_suplier from suplier where kode_suplier='$suplier'");
			$n = mysql_fetch_array($m);
			$nama_suplier = $n['nama_suplier'];
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_barang_masuk_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['bmdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['bmsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$suplier = $_POST['lapbmsup'];//
		header("location:lap_barang_masuk_excel.php?dari=$dari&sampai=$sampai&suplier=".urlencode($suplier));
	}else if($_POST['aksi']=='data suplier'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$suplier = $_POST['suplier'];
		if($suplier=='SEMUA'){
			$a = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where tgl_masuk between '$dari' and '$sampai')");
		}else{
			$a = mysql_query("select * from suplier where kode_suplier in(select kode_suplier from barang_masuk where kode_suplier='$suplier' and tgl_masuk between '$dari' and '$sampai')");
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