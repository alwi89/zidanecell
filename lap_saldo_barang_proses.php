<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data';
$_POST['lscabang']='6';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$id_cabang = escape($_POST['lscabang']);
		$a = mysql_query("select * from barang order by tipe asc, nama_barang asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$qSaldo = mysql_query("select saldo, saldo_rusak from history_saldo where id_cabang='$id_cabang' and kode='$b[kode]'");
				$jmlQuery = mysql_num_rows($qSaldo);
				if($jmlQuery==0){
					$saldo = '0';
					$saldo_rusak = '0';
				}else{
					$hSaldo = mysql_fetch_array($qSaldo);
					$saldo = $hSaldo['saldo'];
					$saldo_rusak = $hSaldo['saldo_rusak'];
				}
				$data[] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'tipe' => $b['tipe'], 'harga_modal' => $b['harga_modal'], 'saldo' => $saldo, 'saldo_rusak' => $saldo_rusak);
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='data cabang'){
		$a = mysql_query("select * from cabang order by nama_cabang asc");
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
		$id_cabang = escape($_POST['lscabang']);
		$qNama = mysql_query("select nama_cabang from cabang where kode_cabang='$id_cabang'");
		$hNama = mysql_fetch_array($qNama);
		$nama_cabang = $hNama['nama_cabang'];
		$a = mysql_query("select * from barang order by nama_barang asc, tipe asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_saldo_barang_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$id_cabang = escape($_POST['lscabang']);
		$qNama = mysql_query("select nama_cabang from cabang where kode_cabang='$id_cabang'");
		$hNama = mysql_fetch_array($qNama);
		$nama_cabang = $hNama['nama_cabang'];
		header("location:lap_saldo_barang_export.php?id=$id_cabang&nama=$nama_cabang");
	}
	
}
?>