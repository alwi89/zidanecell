<?php
require_once("koneksi.php");
if(isset($_POST['aksi_cabang'])){
	if($_POST['aksi_cabang']=='tambah'){
		$kode_cabang = escape($_POST['kode_cabang']);
		$nama_cabang = escape($_POST['nama_cabang']);
		$alamat = escape($_POST['alamat_cabang']);
		$owner = escape($_POST['owner']);
		$jenis = escape($_POST['jenis_cabang']);
		$a = mysql_query("insert into cabang(kode_cabang, nama_cabang, alamat, owner, jenis) values('$kode_cabang', '$nama_cabang', '$alamat', '$owner', '$jenis')");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah cabang');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah cabang');
		}
		echo json_encode($data);
	}else if($_POST['aksi_cabang']=='edit'){
		$kode_lama_cabang = escape($_POST['kode_lama_cabang']);
		$kode_cabang = escape($_POST['kode_cabang']);
		$nama_cabang = escape($_POST['nama_cabang']);
		$alamat = escape($_POST['alamat_cabang']);
		$owner = escape($_POST['owner']);
		$jenis = escape($_POST['jenis_cabang']);
		$a = mysql_query("update cabang set kode_cabang='$kode_cabang', nama_cabang='$nama_cabang', alamat='$alamat', owner='$owner', jenis='$jenis' where kode_cabang='$kode_lama_cabang'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit cabang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit cabang');
		}
		echo json_encode($data);
	}else if($_POST['aksi_cabang']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from cabang where kode_cabang='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi_cabang']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from cabang where kode_cabang='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus cabang');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus cabang');
		}
		echo json_encode($data);
	}else if($_POST['aksi_cabang']=='import'){
		$data_import = explode('@_', $_POST['data']);
		$jml_berhasil = 0;
		$jml_gagal = 0;
		$yang_gagal = "";
		for($i=1; $i<sizeof($data_import); $i++){
			$isi = $data_import[$i];
			$a = mysql_query("insert into cabang(kode_cabang, nama_cabang, alamat, owner, jenis) values($isi)");
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
	$a = mysql_query("select * from cabang order by nama_cabang asc, alamat asc");
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