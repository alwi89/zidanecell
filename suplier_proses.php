<?php
require_once("koneksi.php");
if(isset($_POST['aksi_suplier'])){
	if($_POST['aksi_suplier']=='tambah'){
		$kode_suplier = escape($_POST['kode_suplier']);
		$nama_suplier = escape($_POST['nama_suplier']);
		$alamat = escape($_POST['alamat_suplier']);
		$no_tlp = escape($_POST['no_tlp_suplier']);
		$a = mysql_query("insert into suplier(kode_suplier, nama_suplier, alamat, no_tlp) values('$kode_suplier', '$nama_suplier', '$alamat', '$no_tlp')");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menambah suplier');
		}else{
			$data[] = array('status' => 'failed', 'pesan' => 'gagal menambah suplier');
		}
		echo json_encode($data);
	}else if($_POST['aksi_suplier']=='edit'){
		$kode_lama_suplier = escape($_POST['kode_lama_suplier']);
		$kode_suplier = escape($_POST['kode_suplier']);
		$nama_suplier = escape($_POST['nama_suplier']);
		$alamat = escape($_POST['alamat_suplier']);
		$no_tlp = escape($_POST['no_tlp_suplier']);
		$a = mysql_query("update suplier set kode_suplier='$kode_suplier', nama_suplier='$nama_suplier', alamat='$alamat', no_tlp='$no_tlp' where kode_suplier='$kode_lama_suplier'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil mengedit suplier');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'berhasil mengedit suplier');
		}
		echo json_encode($data);
	}else if($_POST['aksi_suplier']=='preview'){
		$kode = $_POST['id'];
		$a = mysql_query("select * from suplier where kode_suplier='$kode'");
		$data[] = mysql_fetch_array($a);
		echo json_encode($data);
	}else if($_POST['aksi_suplier']=='hapus'){
		$kode = $_POST['id'];
		$a = mysql_query("delete from suplier where kode_suplier='$kode'");
		if($a){
			$data[] = array('status' => 'ok',  'pesan' => 'berhasil menghapus suplier');
		}else{
			$data[] = array('status' => 'failed',  'pesan' => 'gagal menghapus suplier');
		}
		echo json_encode($data);
	}else if($_POST['aksi_suplier']=='import'){
		$data_import = explode('@_', $_POST['data']);
		$jml_berhasil = 0;
		$jml_gagal = 0;
		$yang_gagal = "";
		for($i=1; $i<sizeof($data_import); $i++){
			$isi = $data_import[$i];
			$a = mysql_query("insert into suplier(kode_suplier, nama_suplier, alamat, no_tlp) values($isi)");
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
	$a = mysql_query("select * from suplier order by nama_suplier asc, alamat asc");
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