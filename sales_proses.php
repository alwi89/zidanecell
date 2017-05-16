<?php
require_once("koneksi.php");
session_start();
$id_cabang = $_SESSION['cbg'];
if(isset($_POST['aksi_sales'])){
	if($_POST['aksi_sales']=='tambah'){
		$kode_sales = escape($_POST['kode_sales']);
		$nama_sales = escape($_POST['nama_sales']);
		$alamat = escape($_POST['alamat']);
		$no_tlp = escape($_POST['no_tlp']);
		$pin_bb = escape($_POST['pin_bb']);
		$a = mysql_query("insert into sales(kode_sales, nama_sales, alamat, no_tlp, pin_bb, id_cabang) values('$kode_sales', '$nama_sales', '$alamat', '$no_tlp', '$pin_bb', '$id_cabang')");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah sales');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah sales');
		}
		echo json_encode($data);
	}else if($_POST['aksi_sales']=='edit'){
		$kode_lama_sales = escape($_POST['kode_lama_sales']);
		$kode_sales = escape($_POST['kode_sales']);
		$nama_sales = escape($_POST['nama_sales']);
		$alamat = escape($_POST['alamat']);
		$no_tlp = escape($_POST['no_tlp']);
		$pin_bb = escape($_POST['pin_bb']);
		$a = mysql_query("update sales set kode_sales='$kode_sales', nama_sales='$nama_sales', alamat='$alamat', no_tlp='$no_tlp', pin_bb='$pin_bb' where kode_sales='$kode_lama_sales'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit sales');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit sales');
		}
		echo json_encode($data);
	}else if($_POST['aksi_sales']=='history'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from history_harga where kode='$kode' order by id_history desc");
		while($b = mysql_fetch_array($a)){
			$data[] = $b;
		}
		echo json_encode($data);
	}else if($_POST['aksi_sales']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from sales where kode_sales='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi_sales']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from sales where kode_sales='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus sales');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus sales');
		}
		echo json_encode($data);
	}else if($_POST['aksi_sales']=='import'){
		$data_import = explode('@_', $_POST['data']);
		$jml_berhasil = 0;
		$jml_gagal = 0;
		$yang_gagal = "";
		for($i=1; $i<sizeof($data_import); $i++){
			$isi = $data_import[$i];
			$a = mysql_query("insert into sales(kode_sales, nama_sales, alamat, no_tlp, pin_bb, id_cabang) values($isi, '$id_cabang')");
			//$cek = "insert into barang(kode, nama_barang, tipe, harga_modal) values($isi)";
			if($a){
				$jml_berhasil += 1;
			}else{
				$jml_gagal += 1;
				if($yang_gagal==""){
					$yang_gagal = $isi.' =&gt; '.mysql_error();
				}else{
					$yang_gagal .= "<br />$isi".' =&gt; '.mysql_error();
				}
			}
			//$data[] = array('status' => 'ok',  'pesan' => "import berhasil : $jml_berhasil<br />import gagal : $jml_gagal<br />$cek");
		}		
		$data[] = array('status' => 'ok',  'pesan' => "import berhasil : $jml_berhasil<br /><span style='color:red;'>import gagal : $jml_gagal<br />$yang_gagal</span>");
		echo json_encode($data);
	}
}
if(isset($_GET['data'])){
	$a = mysql_query("select * from sales where id_cabang='$id_cabang' order by nama_sales asc, alamat asc");
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
		
}
?>