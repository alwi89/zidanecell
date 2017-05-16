<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
$jns = $_SESSION['jns'];
/*
$_POST['aksi']='data';
$_POST['dari']='01/03/2016';
$_POST['sampai']='31/03/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$sales = $_POST['sales'];	
		$a = mysql_query("select no_nota, nama_sales, no_tlp, pin_bb, total, dibayar, kekurangan, potongan, tgl_tempo from grosir g join sales s on g.kode_sales=s.kode_sales where g.kode_sales='$sales' and kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai' order by tgl_tempo asc");
		//select no_nota, nama_sales, no_tlp, pin_bb, total, dibayar, kekurangan, potongan, tgl_tempo from grosir g join sales s on g.kode_sales=s.kode_sales where kode_sales='$sales' and kekurangan<>'0' and g.id_cabang='$id_cabang' and tgl_tempo between '$dari' and '$sampai'
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
		$daris = explode("/", escape($_POST['tsdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tssampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$sales = $_POST['laptssales'];
		if($sales=='ALL'){
			$x = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
			$nama_sales = "semua sales dari cabang dan pusat";
		}else if($sales=='SEMUA'){
			$x = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' and kode_sales in(select kode_sales from grosir where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
			$nama_sales = "semua sales yang dipusat";
		}else{
			$x = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where kekurangan<>'0' and grosir.kode_sales='$sales' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
			$q_nama_sales = mysql_query("select * from sales where kode_sales='$sales'");
			$h_nama_sales = mysql_fetch_array($q_nama_sales);
			$nama_sales = $h_nama_sales['nama_sales'];
		}
		$jml = mysql_num_rows($x);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_tagihan_sales_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['tsdari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['tssampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		header("location:lap_tagihan_sales_excel.php?dari=$dari&sampai=$sampai");
	}else if($_POST['aksi']=='data sales'){
		if($jns=='pusat'){
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang order by nama_cabang asc, nama_sales asc");
			$jml = mysql_num_rows($a);
			if($jml==0){
				$data[] = NULL;
			}else{
				while($b = mysql_fetch_array($a)){
					$data[] = array('jenis' => 'pusat', 'kode_sales' => $b['kode_sales'], 'nama_sales' => $b['nama_sales'], 'nama_cabang' => $b['nama_cabang']);
				}
			}
		}else{
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' order by nama_cabang asc, nama_sales asc");
			$jml = mysql_num_rows($a);
			if($jml==0){
				$data[] = NULL;
			}else{
				while($b = mysql_fetch_array($a)){
					$data[] = array('jenis' => 'cabang', 'kode_sales' => $b['kode_sales'], 'nama_sales' => $b['nama_sales'], 'nama_cabang' => $b['nama_cabang']);
				}
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi']=='list sales'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$sales = $_POST['sales'];
		if($sales=='ALL'){
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
		}else if($sales=='SEMUA'){
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where id_cabang='$id_cabang' and kode_sales in(select kode_sales from grosir where kekurangan<>'0' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
		}else{
			$a = mysql_query("select s.*, nama_cabang from sales s join cabang c on s.id_cabang=c.kode_cabang where kode_sales in(select kode_sales from grosir where kekurangan<>'0' and grosir.kode_sales='$sales' and tgl_tempo between '$dari' and '$sampai') order by nama_cabang asc, nama_sales asc");
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