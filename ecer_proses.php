<?php
session_start();
$id_cabang = $_SESSION['cbg'];
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
if(isset($_POST['aksi_ec'])){
	if($_POST['aksi_ec']=='tambah'){
		$no_nota = escape($_POST['no_nota_ec']);
		$tgl_keluars = explode("/", escape($_POST['tgl_keluar_ec']));
		$tgl_keluar = $tgl_keluars[2].'-'.$tgl_keluars[1].'-'.$tgl_keluars[0];
		$username = $_SESSION['usrzdncl'];
		$dibayar = escape($_POST['dibayar']);
		$kembali = escape($_POST['kembali']);
		
		$a = mysql_query("insert into ecer(no_nota, tgl_keluar, username, id_cabang, dibayar, kembali) values('$no_nota', '$tgl_keluar', '$username', '$id_cabang', '$dibayar', '$kembali')");
		if($a){
			$items = explode(',', $_SESSION['bec']);
			$contents = array();
			foreach ($items as $item) {
				$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
			}
			$total = 0;
			foreach ($contents as $id=>$qty) {
				$temp = explode('@', $id);
				$id = $temp[0];
				$harga = $temp[1];
				$b = mysql_query("select * from barang where kode='$id'");
				$c = mysql_fetch_array($b);
				$total += ($harga*$qty);
				$d = mysql_query("insert into ecer_detail(kode, qty, no_nota, harga, harga_modal) values('$c[kode]', '$qty', '$no_nota', '$harga', '$c[harga_modal]')");
				if($d){
					//update pengurangan saldo cabang yang login
					mysql_query("update history_saldo set saldo=saldo-$qty where id_cabang='$id_cabang' and kode='$c[kode]'");
					if(mysql_affected_rows()==0){
						mysql_query("insert into history_saldo(kode, tanggal, saldo, id_cabang) values('$c[kode]', now(), '-$qty', '$id_cabang')");
					}
					$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from ecer_detail where no_nota='$no_nota'");
					$hHit = mysql_fetch_array($hit);
					mysql_query("update ecer set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
					unset($_SESSION['bec']);
					$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan transaksi ecer, no nota : '.$no_nota, 'no_nota' => $no_nota);
				}else{
					$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi, tapi gagal menyimpan barang transaksi');
				}
			}
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi');
		}
		echo json_encode($data);
	}else if($_POST['aksi_ec']=='edit'){
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
	}else if($_POST['aksi_ec']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['aksi_ec']=='data barang'){
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
	}else if($_POST['aksi_ec']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from karyawan where username='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus karyawan');
		}
		echo json_encode($data);
	}else if($_POST['aksi_ec']=='reset'){
		unset($_SESSION['bec']);
		$data[] = array('status' => 'berhasil reset');
		echo json_encode($data);
	}
}
if(isset($_GET['cart'])){
//	unset($_SESSION['bec']);
	if(!isset($_SESSION['bec'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['bec']);
		$contents = array();
		foreach ($items as $item) {
			$contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
		}
		$total = 0;
		foreach ($contents as $id=>$qty) {
	//		echo '*'.$id.'*';
			$dbarang = explode('@', $id);
			$id = $dbarang[0];
			$harga = $dbarang[1];
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += ($harga*$qty);
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'tipe' => $b['tipe'], 'qty' => $qty, 'harga' => $harga, 'sub_total' => ($harga*$qty), 'total' => $total);
		}
		echo json_encode($data);	
	}
}else if(isset($_GET['add'])){
//$_POST['kode']='12345';
//$_POST['
	$q = mysql_query("select * from barang where kode='$_POST[eckode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['eckode']);
		$harga = escape($_POST['echarga']);
		$qty = escape($_POST['ecqty']);
		if(isset($_SESSION['bec'])){
			$newcart = '';
			$temp = explode(',', $_SESSION['bec']);
			for($i=0; $i<sizeof($temp); $i++){
				$dcart = explode('@', $temp[$i]);
				if($dcart[0]==$kode){
					if ($newcart != '') {
						$newcart .= ','.$kode.'@'.$harga;
					} else {
						$newcart = $kode.'@'.$harga;
					}
				}else{
					if ($newcart != '') {
						$newcart .= ','.$temp[$i];
					} else {
						$newcart = $temp[$i];
					}
				}
			}
			for($i=1; $i<=$qty; $i++){
				$newcart .= ','.$kode.'@'.$harga;
			}
			$_SESSION['bec'] = $newcart;
		}else{
			for($i=1; $i<=$qty; $i++){
				if(!isset($_SESSION['bec'])){
					$_SESSION['bec'] = $kode.'@'.$harga;
				}else{
					$_SESSION['bec'] .= ','.$kode.'@'.$harga;
				}
			}
		}		
	}
	$data[] = array('status' => 'add to cart');
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['bec']);
		$newcart = '';
			foreach ($items as $item) {
				$temp = explode('@', $item);
				$cek = $temp[0];
				if ($_POST['eckode'] != $cek) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
		if($newcart!=''){	
			$_SESSION['bec'] = $newcart;
			$data[] = array('status' => 'remove from cart');
		}else{
			unset($_SESSION['bec']);
			$data[] = array('status' => '0');
		}
	
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['bec'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['get_nota'])){
	$a = mysql_query("SELECT max(cast(SUBSTRING_INDEX(no_nota,'-',-1) as unsigned)) no_nota from ecer where substr(no_nota, 1, 3)='ecr'");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$no_nota = "ecr-1";
	}else{
		$b = mysql_fetch_array($a);
		$last = $b['no_nota'];
		$no_nota = "ecr-".($last+1);		
	}
	$data[] = array('no_nota' => $no_nota);
	echo json_encode($data);
}else if(isset($_GET['reset'])){
	unset($_SESSION['bec']);
	$data[] = array('status' => 'reseted');
	echo json_encode($data);
}
?>