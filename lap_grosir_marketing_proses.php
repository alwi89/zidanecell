<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
$jns = $_SESSION['jns'];
/*
$_POST['aksi']='data';
$_POST['dari']='01/03/2016';
$_POST['sampai']='30/11/2016';
$_POST['lapgrosmarketing']='Alwi';
$_POST['stts_marketing']='all';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$marketing = $_POST['lapgrosmarketing'];
		$status = $_POST['stts_marketing'];
		if($status=='all'){
			$kekurangan = '';
		}else if($status=='lunas'){
			$kekurangan='kekurangan=0 and ';
		}else{
			$kekurangan='kekurangan<>0 and ';
		}
		$a = mysql_query("select m.no_nota, dibayar, kekurangan, potongan, tgl_keluar, m.no_nota, nama, nama_sales, d.kode, tipe, nama_barang, d.harga_modal, d.harga, qty, d.harga*qty sub_total from karyawan k join grosir m on k.username=m.username join grosir_detail d on m.no_nota=d.no_nota left join barang b on d.kode=b.kode join sales c on m.kode_sales=c.kode_sales where marketing='$marketing' and $kekurangan tgl_keluar between '$dari' and '$sampai' order by tgl_keluar asc, no_nota asc");
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
		$daris = explode("/", escape($_POST['grosir_marketingdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['grosir_marketingsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$marketing = $_POST['lapgrosmarketing'];
		$status = $_POST['stts_marketing'];
		if($status=='all'){
			$status='';
		}else if($status=='lunas'){
			$status='kekurangan=0 and ';
		}else{
			$status='kekurangan<>0 and ';
		}
		if($marketing=='SEMUA'){
			$x = mysql_query("select marketing from grosir where $status tgl_keluar between '$dari' and '$sampai' group by marketing order by marketing asc");
			$nama_marketing = "semua marketing";
		}else{
			$x = mysql_query("select marketing from grosir where marketing='$marketing' and $status tgl_keluar between '$dari' and '$sampai' group by marketing order by marketing asc");
			if($marketing==''){
				$nama_marketing = 'tanpa nama marketing';
			}else{
				$nama_marketing = $marketing;
			}
			
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_grosir_marketing_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['grosir_marketingdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['grosir_marketingsampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$marketing = urlencode($_POST['lapgrosmarketing']);
		$status = urlencode($_POST['stts_marketing']);
		header("location:lap_grosir_marketing_excel.php?dari=$dari&sampai=$sampai&status=$status&marketing=$marketing");
	}else if($_POST['aksi']=='data marketing'){
		$a = mysql_query("select marketing from grosir group by marketing order by marketing asc");
			$jml = mysql_num_rows($a);
			if($jml==0){
				$data[] = NULL;
			}else{
				while($b = mysql_fetch_array($a)){
					$data[] = $b;
				}
			}
		
		echo json_encode($data);
	}else if($_POST['aksi']=='list marketing'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$marketing = $_POST['marketing'];
		$status = $_POST['status'];
		if($status=='all'){
			$status = '';
		}else if($status=='lunas'){
			$status = 'kekurangan=0 and ';
		}else if($status=='belum lunas'){
			$status = 'kekurangan<>0 and ';
		}
		if($marketing=='SEMUA'){
			$a = mysql_query("select marketing from grosir where $status tgl_keluar between '$dari' and '$sampai' group by marketing order by marketing asc");
		}else{
			$a = mysql_query("select marketing from grosir where marketing='$marketing' and $status tgl_keluar between '$dari' and '$sampai' group by marketing order by marketing asc");
		}
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}
	
}
?>