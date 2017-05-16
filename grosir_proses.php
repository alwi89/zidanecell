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
if(isset($_POST['aksi_grosir'])){
	if($_POST['aksi_grosir']=='tambah'){
		$no_nota = escape($_POST['no_nota_grosir']);
		$marketing = escape($_POST['marketing']);
		$tgl_keluars = explode("/", escape($_POST['tgl_keluar_grosir']));
		$tgl_keluar = $tgl_keluars[2].'-'.$tgl_keluars[1].'-'.$tgl_keluars[0];
		$username = $_SESSION['usrzdncl'];
		$sales = escape($_POST['sales']);
		if($_POST['kekurangan']!='0'){
			$tgl_tempos = explode("/", escape($_POST['tgl_tempo']));
			$tgl_tempo = "'".$tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0]."'";
		}else{
			$tgl_tempo = 'NULL';
		}
		$total = escape($_POST['total']);//sementara
		$dibayar = escape($_POST['dibayar']);
		$kekurangan = escape($_POST['kekurangan']);
		$potongan = escape($_POST['potongan']);
		$ket_potongan = escape($_POST['ket_potongan']);
		$a = mysql_query("insert into grosir(total, jumlah, no_nota, tgl_keluar, username, kode_sales, dibayar, kekurangan, tgl_tempo, potongan, ket_potongan, id_cabang, marketing) values('$total', '0', '$no_nota', '$tgl_keluar', '$username', '$sales', '$dibayar', '$kekurangan', $tgl_tempo, '$potongan', '$ket_potongan', '$id_cabang', '$marketing')");
		mysql_query("insert into history_pembayaran_grosir(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values('$tgl_keluar', '$dibayar', '$kekurangan', '$no_nota')");
		if($a){
			if(isset($_SESSION['bgrosir'])){
				$items = explode(',', $_SESSION['bgrosir']);
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
					$d = mysql_query("insert into grosir_detail(kode, qty, no_nota, harga, harga_modal) values('$c[kode]', '$qty', '$no_nota', '$harga', '$c[harga_modal]')");
					if($d){
						//update pengurangan saldo cabang yang login
						mysql_query("update history_saldo set saldo=saldo-$qty where id_cabang='$id_cabang' and kode='$c[kode]'");
						if(mysql_affected_rows()==0){
							mysql_query("insert into history_saldo(kode, tanggal, saldo, id_cabang) values('$c[kode]', '$tgl_keluar', '-$qty', '$id_cabang')");
						}
						$hit = mysql_query("select sum(qty) as jml, sum(harga*qty) as total from grosir_detail where no_nota='$no_nota'");
						$hHit = mysql_fetch_array($hit);
						mysql_query("update grosir set total='$hHit[total]', jumlah='$hHit[jml]' where no_nota='$no_nota'");
						unset($_SESSION['bgrosir']);
						$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan transaksi grosir, no nota : '.$no_nota, 'no_nota' => $no_nota);
					}else{
						$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi tanpa ada barang');
					}
				}
			}else{
				mysql_query("insert into grosir_detail(kode, qty, no_nota, harga, harga_modal) values(null, '1', '$no_nota', '$total', '$total')");
				$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan transaksi grosir tanpa barang, no nota : '.$no_nota, 'no_nota' => $no_nota);
			}
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi => '.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_grosir']=='edit'){
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
	}else if($_POST['aksi_grosir']=='cek'){
		$username = $_POST['username'];
		$a = mysql_query("select * from karyawan where username='$username'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = array('status' => 'ok');
		}else{
			$data[] = array('status' => 'no');
		}
		echo json_encode($data);
	}else if($_POST['aksi_grosir']=='data barang'){
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
	}else if($_POST['aksi_grosir']=='data sales'){
		$a = mysql_query("select * from sales where id_cabang='$id_cabang' order by nama_sales asc");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi_grosir']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from karyawan where username='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus karyawan');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus karyawan');
		}
		echo json_encode($data);
	}else if($_POST['aksi_grosir']=='reset'){
		unset($_SESSION['bgrosir']);
		$data[] = array('status' => 'berhasil reset');
		echo json_encode($data);
	}
}
if(isset($_GET['cart'])){
//	unset($_SESSION['bkc']);
	if(!isset($_SESSION['bgrosir'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['bgrosir']);
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
	$q = mysql_query("select * from barang where kode='$_POST[grosirkode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['grosirkode']);
		$harga = escape($_POST['grosirharga']);
		$qty = escape($_POST['grosirqty']);
		if(isset($_SESSION['bgrosir'])){
			$newcart = '';
			$temp = explode(',', $_SESSION['bgrosir']);
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
			$_SESSION['bgrosir'] = $newcart;
		}else{
			for($i=1; $i<=$qty; $i++){
				if(!isset($_SESSION['bgrosir'])){
					$_SESSION['bgrosir'] = $kode.'@'.$harga;
				}else{
					$_SESSION['bgrosir'] .= ','.$kode.'@'.$harga;
				}
			}
		}		
	}
	$data[] = array('status' => 'add to cart');
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['bgrosir']);
	
		$newcart = '';
			foreach ($items as $item) {
				$temp = explode('@', $item);
				$cek = $temp[0];
				if ($_POST['grosirkode'] != $cek) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
		if($newcart!=''){	
			$_SESSION['bgrosir'] = $newcart;
			$data[] = array('status' => 'remove from cart');
		}else{
			unset($_SESSION['bgrosir']);
			$data[] = array('status' => '0');
		}
	
	
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['bgrosir'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['get_nota'])){
	$a = mysql_query("SELECT max(cast(SUBSTRING_INDEX(no_nota,'-',-1) as unsigned)) no_nota from grosir where substr(no_nota, 1, 3)='grs'");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$no_nota = "grs-1";
	}else{
		$b = mysql_fetch_array($a);
		$last = $b['no_nota'];
		$no_nota = "grs-".($last+1);		
	}
	$data[] = array('no_nota' => $no_nota);
	echo json_encode($data);
}else if(isset($_GET['reset'])){
	unset($_SESSION['bgrosir']);
	$data[] = array('status' => 'reseted');
	echo json_encode($data);
}
?>