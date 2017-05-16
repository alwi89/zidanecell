<?php
session_start();
/*
$_POST['aksi_barang_masuk']='tambah';
$_POST['no_nota'] = 'ddd';
$_POST['tgl_masuk'] = '27/03/2016';
$_POST['kekurangan']='100000';
$_POST['tgl_tempo']='29/03/2016';
$_POST['dibayar']='200000';
$_POST['bmsuplier']='';
*/
require_once("koneksi.php");
if(isset($_POST['rbmaksi_barang_masuk'])){
	if($_POST['rbmaksi_barang_masuk']=='tambah'){
		$no_nota = escape($_POST['no_nota']);
		$tgl_masuks = explode("/", escape($_POST['tgl_masuk']));
		$tgl_masuk = $tgl_masuks[2].'-'.$tgl_masuks[1].'-'.$tgl_masuks[0];
		$username = $_SESSION['usrzdncl'];
		$suplier = escape($_POST['rbmsuplier']);
		$id_cabang = $_SESSION['cbg'];
		if($suplier==''){
			$suplier='NULL';
		}else{
			$suplier="'$suplier'";
		}
		$a = mysql_query("insert into return_barang_masuk(no_nota, tgl_return, username, kode_suplier) values('$no_nota', '$tgl_masuk', '$username', $suplier)");
		if($a){
			$items = explode(',', $_SESSION['rbmasuk']);
			$contents = array();
			foreach ($items as $item) {
				$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
			}
			$total = 0;
			foreach ($contents as $id=>$qty) {
				$b = mysql_query("select * from barang where kode='$id'");
				$c = mysql_fetch_array($b);
				$total += $qty;
				$d = mysql_query("insert into return_barang_masuk_detail(kode, qty, no_nota) values('$c[kode]', '$qty', '$no_nota')");
				if($d){
					mysql_query("update history_saldo set saldo_rusak=saldo_rusak-$qty where id_cabang='$id_cabang' and kode='$c[kode]'");
					if(mysql_affected_rows()==0){
						mysql_query("insert into history_saldo(kode, tanggal, saldo_rusak, id_cabang) values('$c[kode]', now(), '-$qty', '$id_cabang')");
					}
					$hit = mysql_query("select sum(qty) as jml from return_barang_masuk_detail where no_nota='$no_nota'");
					$hHit = mysql_fetch_array($hit);
					mysql_query("update return_barang_masuk set jumlah='$hHit[jml]' where no_nota='$no_nota'");
					unset($_SESSION['rbmasuk']);
					$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan return suplier');
				}else{
					$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi, gagal menyimpan barang transaksi');
				}
			}
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi');
		}
		echo json_encode($data);
	}else if($_POST['rbmaksi_barang_masuk']=='edit'){
		$kode_lama_karyawan = escape($_POST['kode_lama_karyawan']);
		$username = escape($_POST['username']);
		$nama_karyawan = escape($_POST['nama_karyawan']);
		$no_tlp = escape($_POST['no_tlp_karyawan']);
		$pin_bb = escape($_POST['pin_bb_karyawan']);
		$password = escape($_POST['password']);
		$level = escape($_POST['level']);
		$status = escape($_POST['status']);
		$a = mysql_query("update karyawan set username='$username', nama='$nama_karyawan', no_telp='$no_tlp', pin_bb='$pin_bb', password='$password', level='$level', status='$status' where username='$kode_lama_karyawan'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit karyawan');
		}
		echo json_encode($data);
	}else if($_POST['rbmaksi_barang_masuk']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['rbmaksi_barang_masuk']=='data barang'){
		$a = mysql_query("select * from barang order by nama_barang asc, tipe asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
			echo json_encode($data);
		}
	}else if($_POST['rbmaksi_barang_masuk']=='data suplier'){
		$a = mysql_query("select * from suplier order by nama_suplier asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
			echo json_encode($data);
		}
	}else if($_POST['rbmaksi_barang_masuk']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from karyawan where username='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus karyawan');
		}
		echo json_encode($data);
	}
}
if(isset($_GET['cart'])){
	if(!isset($_SESSION['rbmasuk'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['rbmasuk']);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$total = 0;
		foreach ($contents as $id=>$qty) {
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += $qty;
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'tipe' => $b['tipe'], 'qty' => $qty, 'total' => $total);
		}
		echo json_encode($data);	
	}
}else if(isset($_GET['add'])){
	$q = mysql_query("select * from barang where kode='$_POST[rbmkode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['rbmkode']);
		$qty = escape($_POST['rbmqty']);
		for($i=1; $i<=$qty; $i++){
			if(!isset($_SESSION['rbmasuk'])){
				$_SESSION['rbmasuk'] = $kode;
			}else{
				$_SESSION['rbmasuk'] .= ','.$kode;
			}
		}
	}
	$data[] = array('status' => 'add to cart');
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['rbmasuk']);
	
		$newcart = '';
			foreach ($items as $item) {
				if ($_POST['rbmkode'] != $item) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
	if($newcart!=''){	
		$_SESSION['rbmasuk'] = $newcart;
		$data[] = array('status' => 'remove from cart');
	}else{
		unset($_SESSION['rbmasuk']);
		$data[] = array('status' => '0');
	}
	
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['rbmasuk'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['get_nota'])){
	$kode = 'rbm-';
	$random = rand(100, 999);
	$kode .= $random;
	$tgl = date('dmYHis');
	$kode .= $tgl;
	$data[] = array('no_nota' => $kode);
	echo json_encode($data);
}
?>