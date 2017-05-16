<?php
session_start();
$id_cabang = $_SESSION['cbg'];
/*
$_POST['no_nota']='ecr-542';
$_POST['aksi_rec']='cek nama';
$_POST['id']='12345';
$_POST['no_nota']='ECRT10';
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
if(isset($_POST['aksi_rec'])){
	if($_POST['aksi_rec']=='tambah'){
		$no_nota = escape($_POST['no_nota_rec']);
		$jml_returBarang = $_POST['recJmlRetur'];
		$kode = $_POST['reckode'];
		$tgl_keluars = explode("/", escape($_POST['tgl_keluar_rec']));
		$tgl_keluar = $tgl_keluars[2].'-'.$tgl_keluars[1].'-'.$tgl_keluars[0];
		$username = $_SESSION['usrzdncl'];
		$no_nota_ecer = escape($_POST['no_nota_ecr']);
		$a = mysql_query("insert into return_ecer(no_nota, tgl_return, username, no_nota_ecer, id_cabang) values('$no_nota', '$tgl_keluar', '$username', '$no_nota_ecer', '$id_cabang')");
		if($a){
			for($i=0; $i<sizeof($kode); $i++) {
				$kode_barang = escape($kode[$i]);
				$qty_tukar = escape($jml_returBarang[$i]);
				$d = mysql_query("insert into return_ecer_detail(kode, qty, no_nota) values('$kode_barang', '$qty_tukar', '$no_nota')");
				if($d){
					if($qty_tukar!=0){
						//mengurangi saldo barang normal dan menambah saldo barang rusak
						mysql_query("update history_saldo set saldo=saldo-$qty_tukar, saldo_rusak=saldo_rusak+$qty_tukar where id_cabang='$id_cabang' and kode='$kode_barang'");
						if(mysql_affected_rows()==0){
							mysql_query("insert into history_saldo(kode, tanggal, saldo, saldo_rusak, id_cabang) values('$kode_barang', now(), '-$qty_tukar', '$qty_tukar', '$id_cabang')");
						}
					}
					$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan transaksi return ecer', 'no_nota' => $no_nota);
				}else{
					$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi, tapi gagal menyimpan barang transaksi');
				}
			}
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi');
		}
		echo json_encode($data);
	}else if($_POST['aksi_rec']=='edit'){
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
	}else if($_POST['aksi_rec']=='cek harga'){
		$kode = $_POST['id'];
		$no_nota = $_POST['no_nota'];
		$a = mysql_query("select qty, harga, harga_modal from ecer_detail where no_nota='$no_nota' and kode='$kode'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			$data[] = mysql_fetch_array($a);
		}
		echo json_encode($data);
	}else if($_POST['aksi_rec']=='cek nama'){
		$no_nota = $_POST['no_nota'];
		$a = mysql_query("select b.nama_barang, b.tipe, g.*, d.* from ecer g join ecer_detail d on g.no_nota=d.no_nota join barang b on d.kode=b.kode where g.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			$data[] = mysql_fetch_array($a);
		}
		echo json_encode($data);
	}else if($_POST['aksi_rec']=='data barang'){
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
	}else if($_POST['aksi_rec']=='hapus'){
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
	//$_POST['no_nota'] = 'grs-585';
	$no_nota = $_POST['no_nota'];
	$a = mysql_query("select b.nama_barang, b.tipe, g.*, d.* from ecer g join ecer_detail d on g.no_nota=d.no_nota left join barang b on d.kode=b.kode where g.no_nota='$no_nota'");
	$jml = mysql_num_rows($a);
	if($jml==0){
		echo '{			
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		while($b = mysql_fetch_array($a)){
			$data['aaData'][] = $b;
		}
		echo json_encode($data);
	}
	/*
//	unset($_SESSION['brec']);
	if(!isset($_SESSION['brec'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['brec']);
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
	*/
}else if(isset($_GET['add'])){
//$_POST['kode']='12345';
//$_POST['
	$q = mysql_query("select * from barang where kode='$_POST[reckode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['reckode']);
		$harga = escape($_POST['recharga']);
		$qty = escape($_POST['recqty']);
		if(isset($_SESSION['brec'])){
			$newcart = '';
			$temp = explode(',', $_SESSION['brec']);
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
			$_SESSION['brec'] = $newcart;
		}else{
			for($i=1; $i<=$qty; $i++){
				if(!isset($_SESSION['brec'])){
					$_SESSION['brec'] = $kode.'@'.$harga;
				}else{
					$_SESSION['brec'] .= ','.$kode.'@'.$harga;
				}
			}
		}		
	}
	$data[] = array('status' => 'add to cart');
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['brec']);
	if(sizeof($items)==1){
		unset($_SESSION['brec']);
		$data[] = array('status' => '0');
	}else{
		$newcart = '';
			foreach ($items as $item) {
				$temp = explode('@', $item);
				$cek = $temp[0];
				if ($_POST['reckode'] != $cek) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
		$_SESSION['brec'] = $newcart;
		$data[] = array('status' => 'remove from cart');
	}
	
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['brec'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['get_nota'])){
	$kode = 'rec-';
	$random = rand(100, 999);
	$kode .= $random;
	$tgl = date('dmYHis');
	$kode .= $tgl;
	$data[] = array('no_nota' => $kode);
	echo json_encode($data);
}
?>