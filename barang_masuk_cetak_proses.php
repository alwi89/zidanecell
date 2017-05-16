<?php
require_once("koneksi.php");

if(isset($_POST['aksi'])){
	if($_POST['aksi']=='tambah'){
		$kode = escape($_POST['kode']);
		$nama_barang = escape($_POST['nama_barang']);
		$tipe = escape($_POST['tipe']);
		$harga_modal = escape($_POST['harga_modal']);
		$a = mysql_query("insert into barang(kode, nama_barang, tipe, harga_modal) values('$kode', '$nama_barang', '$tipe', '$harga_modal')");
		if($a){
			mysql_query("insert into history_harga(tgl_perubahan, kode, harga) values(now(), '$kode', '$harga_modal')");
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah barang');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah barang');
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='edit'){
		$kode_lama = escape($_POST['kode_lama']);
		$kode = escape($_POST['kode']);
		$nama_barang = escape($_POST['nama_barang']);
		$tipe = escape($_POST['tipe']);
		$harga_modal = escape($_POST['harga_modal']);
		$a = mysql_query("update barang set kode='$kode', nama_barang='$nama_barang', tipe='$tipe', harga_modal='$harga_modal' where kode='$kode_lama'");
		if($a){
			mysql_query("insert into history_harga(tgl_perubahan, kode, harga) values(now(), '$kode', '$harga_modal')");
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit barang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit barang');
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='history'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from history_harga where kode='$kode' order by id_history desc");
		while($b = mysql_fetch_array($a)){
			$data[] = $b;
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from barang where kode='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from barang where kode='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus barang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus barang');
		}
		echo json_encode($data);
	}
}
if(isset($_GET['data'])){
	$a = mysql_query("select * from barang order by nama_barang asc, tipe asc");
	$jml = mysql_num_rows($a);
	if($jml==0){
		$data[] = NULL;
	}else{
		while($b = mysql_fetch_array($a)){
			$data['aaData'][] = $b;
		}
	}
	echo json_encode($data);	
}
?>