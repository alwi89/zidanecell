<?php
session_start();
$id_cabang = $_SESSION['cbg'];
/*
$_POST['aksi_rec']='cek harga';
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

//$_POST['aksi_rgr'] = 'tambah';
//$_POST['no_nota_rgr'] = '12';
require_once("koneksi.php");
if(isset($_POST['aksi_rgr'])){
	if($_POST['aksi_rgr']=='tambah'){
		$no_nota = escape($_POST['no_nota_rgr']);
		$jml_returBarang = $_POST['rgrJmlRetur'];
		$jml_potongNota = $_POST['rgrJmlPotong'];
		$kode = $_POST['rgrkode'];
		$tgl_keluars = explode("/", escape($_POST['tgl_keluar_rgr']));
		$tgl_keluar = $tgl_keluars[2].'-'.$tgl_keluars[1].'-'.$tgl_keluars[0];
		$username = $_SESSION['usrzdncl'];
		$no_nota_grosir = escape($_POST['no_nota_gr']);
		$a = mysql_query("insert into return_grosir(no_nota, tgl_return, username, no_nota_grosir, id_cabang) values('$no_nota', '$tgl_keluar', '$username', '$no_nota_grosir', '$id_cabang')");
		if($a){
			//ambil teks keterangan potongan grosir yang dulu jika ada
			$q_ket_potongan = mysql_query("select ket_potongan from grosir where no_nota='$no_nota_grosir'");
			$h_ket_potongan = mysql_fetch_array($q_ket_potongan);
			$keterangan = $h_ket_potongan['ket_potongan'];
			for($i=0; $i<sizeof($kode); $i++) {
				$kode_barang = escape($kode[$i]);
				$qty_potong = escape($jml_potongNota[$i]);
				$qty_tukar = escape($jml_returBarang[$i]);
				$d = mysql_query("insert into return_grosir_detail(kode, qty_potong, qty_tukar, no_nota) values('$kode_barang', '$qty_potong', '$qty_tukar', '$no_nota')");
				//cari tahu harga ketika transaksi grosir
				$q_harga = mysql_query("select potongan, total, harga, kekurangan, dibayar from grosir_detail d join grosir g on d.no_nota=g.no_nota where g.no_nota='$no_nota_grosir' and kode='$kode_barang'");
				$h_harga = mysql_fetch_array($q_harga);
				if($d){
					//jika dipotong nota
					if($qty_potong!=0){
						$sub_total_potongan = $h_harga['harga']*$qty_potong;
						//update potongan grosir dan keterangan
						mysql_query("update grosir set potongan=potongan+$sub_total_potongan where no_nota='$no_nota_grosir'");
						//cek kekurangan sekarang - seharusnya
						$kekurangan_sekarang = $h_harga['total']-$h_harga['dibayar']-$h_harga['potongan']-$sub_total_potongan;
						if($keterangan==''){
							$keterangan = 'potongan nota retur '.$no_nota.' tgl '.escape($_POST['tgl_keluar_rgr']);
						}else{
							$keterangan .= ', potongan nota retur '.$no_nota.' tgl '.escape($_POST['tgl_keluar_rgr']);
						}
						mysql_query("update grosir set kekurangan='$kekurangan_sekarang', ket_potongan='$keterangan' where no_nota='$no_nota_grosir'");
						//menambah saldo barang rusak
						mysql_query("update history_saldo set saldo_rusak=saldo_rusak+$qty_tukar where id_cabang='$id_cabang' and kode='$kode_barang'");
						if(mysql_affected_rows()==0){
							mysql_query("insert into history_saldo(kode, tanggal, saldo_rusak, id_cabang) values('$kode_barang', now(), '$qty_tukar', '$id_cabang')");
						}
					}
					//jika ditukar barang
					if($qty_tukar!=0){
						//mengurangi saldo barang normal dan menambah saldo barang rusak
						mysql_query("update history_saldo set saldo=saldo-$qty_tukar, saldo_rusak=saldo_rusak+$qty_tukar where id_cabang='$id_cabang' and kode='$kode_barang'");
						if(mysql_affected_rows()==0){
						mysql_query("insert into history_saldo(kode, tanggal, saldo, saldo_rusak, id_cabang) values('$kode_barang', now(), '-$qty_tukar', '$qty_tukar', '$id_cabang')");
						}
					}
					$data[] = array('status' => 'ok',  'pesan' => 'berhasil menyimpan transaksi return grosir', 'no_nota' => $no_nota);
				}else{
					$data[] = array('status' => 'failed',  'pesan' => 'berhasil menyimpan transaksi, tapi gagal menyimpan barang transaksi');
				}
			}
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menyimpan transaksi'.mysql_error());
		}
		echo json_encode($data);
	}else if($_POST['aksi_rgr']=='edit'){
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
	}else if($_POST['aksi_rgr']=='cek harga'){
		$kode = $_POST['id'];
		$no_nota = $_POST['no_nota'];
		$a = mysql_query("select qty, harga, harga_modal from grosir_detail where no_nota='$no_nota' and kode='$kode'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			$data[] = mysql_fetch_array($a);
		}
		echo json_encode($data);
	}else if($_POST['aksi_rgr']=='cek nama'){
		$no_nota = $_POST['no_nota'];
		$a = mysql_query("select b.nama_barang, b.tipe, g.*, d.*, nama_sales from grosir g join grosir_detail d on g.no_nota=d.no_nota join barang b on d.kode=b.kode join sales s on g.kode_sales=s.kode_sales where g.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			$data[] = mysql_fetch_array($a);
		}
		echo json_encode($data);
	}else if($_POST['aksi_rgr']=='data barang'){
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
	}else if($_POST['aksi_rgr']=='hapus'){
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
	$a = mysql_query("select b.nama_barang, b.tipe, g.*, d.*, nama_sales from grosir g join grosir_detail d on g.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales s on g.kode_sales=s.kode_sales where g.no_nota='$no_nota'");
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
//	unset($_SESSION['brgr']);
	if(!isset($_SESSION['brgr'])){
		echo '{
			"sEcho": 1,
			"iTotalRecords": "0",
			"iTotalDisplayRecords": "0",
			"aaData": []
		}';
	}else{
		$items = explode(',', $_SESSION['brgr']);
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
			$qty1 = $dbarang[2];
			$qty2 = $dbarang[3];
			$a = mysql_query("select * from barang where kode='$id'");
			$b = mysql_fetch_array($a);
			$total += ($harga*$qty2);
			$data['aaData'][] = array('kode' => $b['kode'], 'nama_barang' => $b['nama_barang'], 'tipe' => $b['tipe'], 'qty' => $qty1.' tukar barang, '.$qty2.' potong nota', 'harga' => $harga, 'sub_total' => ($harga*$qty2), 'total' => $total);
		}
		echo json_encode($data);	
	}
	*/
}else if(isset($_GET['add'])){
//$_POST['kode']='12345';
//$_POST['
	$q = mysql_query("select * from barang where kode='$_POST[rgrkode]'");
	$cek = mysql_num_rows($q);
	if($cek!=0){
		$kode = escape($_POST['rgrkode']);
		$harga = escape($_POST['rgrharga']);
		$qty1 = escape($_POST['rgrqty1']);
		$qty2 = escape($_POST['rgrqty2']);
		if(isset($_SESSION['brgr'])){
			$newcart = '';
			$temp = explode(',', $_SESSION['brgr']);
			for($i=0; $i<sizeof($temp); $i++){
				$dcart = explode('@', $temp[$i]);
				if($dcart[0]==$kode){
					if ($newcart != '') {
						$newcart .= ','.$kode.'@'.$harga.'@'.$qty1.'@'.$qty2;
					} else {
						$newcart = $kode.'@'.$harga.'@'.$qty1.'@'.$qty2;
					}
				}else{
					if ($newcart != '') {
						$newcart .= ','.$temp[$i];
					} else {
						$newcart = $temp[$i];
					}
				}
			}
			for($i=1; $i<=$qty1; $i++){
				$newcart .= ','.$kode.'@'.$harga.'@'.$qty1.'@'.$qty2;
			}
			$_SESSION['brgr'] = $newcart;
		}else{
			for($i=1; $i<=$qty1; $i++){
				if(!isset($_SESSION['brgr'])){
					$_SESSION['brgr'] = $kode.'@'.$harga.'@'.$qty1.'@'.$qty2;
				}else{
					$_SESSION['brgr'] .= ','.$kode.'@'.$harga.'@'.$qty1.'@'.$qty2;
				}
			}
		}		
	}
	$data[] = array('status' => 'add to cart');
	echo json_encode($data);
}else if(isset($_GET['del'])){
	$items = explode(',',$_SESSION['brgr']);
	
	
		$newcart = '';
			foreach ($items as $item) {
				$temp = explode('@', $item);
				$cek = $temp[0];
				if ($_POST['rgrkode'] != $cek) {
					if ($newcart != '') {
						$newcart .= ','.$item;
					} else {
						$newcart = $item;
					}
				}
			}
	
		if($newcart!=''){	
			$_SESSION['brgr'] = $newcart;
			$data[] = array('status' => 'remove from cart');
		}else{
			unset($_SESSION['brgr']);
			$data[] = array('status' => '0');
		}
	
	
	//$data[] = array('size' => sizeof($items));
	
	
	echo json_encode($data);
}else if(isset($_GET['cek'])){
	if(isset($_SESSION['brgr'])){
		$data[] = array('status' => 'no');
	}else{
		$data[] = array('status' => 'yes');
	}
	echo json_encode($data);
}else if(isset($_GET['get_nota'])){
	$kode = 'rgr-';
	$random = rand(100, 999);
	$kode .= $random;
	$tgl = date('dmYHis');
	$kode .= $tgl;
	$data[] = array('no_nota' => $kode);
	echo json_encode($data);
}
?>