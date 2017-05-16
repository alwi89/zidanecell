<?php
require_once('koneksi.php');
session_start();
$id_cabang = $_SESSION['cbg'];
if(isset($_GET['data_sekarang'])){
		$a = mysql_query("select g.*, nama_sales, no_tlp, pin_bb, datediff(tgl_tempo, curdate()) hit_mundur from grosir g join sales s on g.kode_sales=s.kode_sales where g.id_cabang='$id_cabang' and tgl_tempo BETWEEN curdate() and curdate() + interval 2 day order by tgl_tempo asc, nama_sales asc, kekurangan desc");
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
}else if(isset($_GET['data_sekarang_suplier'])){
		$x = mysql_query("select jenis from cabang where kode_cabang='$id_cabang'");
		$y = mysql_fetch_array($x);
		if($y['jenis']=='pusat'){
			$a = mysql_query("select m.*, nama_suplier, no_tlp, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier where tgl_tempo BETWEEN curdate() and curdate() + interval 2 day order by tgl_tempo asc, nama_suplier asc, kekurangan desc");
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
		}else{
			echo '{
					"sEcho": 1,
					"iTotalRecords": "0",
					"iTotalDisplayRecords": "0",
					"aaData": []
				}';
		}
}else if(isset($_GET['jml'])){
	$qSales = mysql_query("select g.*, nama_sales, no_tlp, pin_bb, datediff(tgl_tempo, curdate()) hit_mundur from grosir g join sales s on g.kode_sales=s.kode_sales where g.id_cabang='$id_cabang' and tgl_tempo BETWEEN curdate() and curdate() + interval 2 day order by tgl_tempo");
	$jmlSales = mysql_num_rows($qSales);
	$x = mysql_query("select jenis from cabang where kode_cabang='$id_cabang'");
	$y = mysql_fetch_array($x);
	if($y['jenis']=='pusat'){
		$qSuplier = mysql_query("select m.*, nama_suplier, no_tlp, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier where tgl_tempo BETWEEN curdate() and curdate() + interval 2 day order by tgl_tempo");
		$jmlSuplier = mysql_num_rows($qSuplier);
	}else{
		$jmlSuplier = 0;
	}
	$jmlTotal = $jmlSales+$jmlSuplier;
	$data[] = array('jml' => $jmlTotal);	
	echo json_encode($data);
	
}
/*
$_POST['aksi_home']='edit tp';
$_POST['tp_no_nota'] = 'BRM';
*/
if(isset($_POST['aksi_home'])){
	if($_POST['aksi_home']=='edit ts'){
		$no_nota = escape($_POST['ts_no_nota']);
		$a = mysql_query("select g.*, tgl_bayar, jumlah_cicil, kekurangan_cicil, nama_sales, no_tlp, pin_bb, datediff(tgl_tempo, curdate()) hit_mundur from grosir g join sales s on g.kode_sales=s.kode_sales left join history_pembayaran_grosir h on g.no_nota=h.no_nota where g.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='simpan'){
		$no_nota = escape($_POST['ts_no_nota']);
		$sudahdibayar = escape($_POST['tssudahdibayar']);
		$dibayar = $sudahdibayar+escape($_POST['ts_dibayar']);
		$tgl_tempos = explode("/", escape($_POST['ts_tgl_tempo']));
		$tgl_tempo = $tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0];
		$kekurangan = escape($_POST['ts_kekurangan']);
		if($kekurangan=='0'){
			$tgl_tempo = 'null';
		}else{
			$tgl_tempo = "'$tgl_tempo'";
		}
		$a = mysql_query("update grosir set tgl_tempo=$tgl_tempo, dibayar='$dibayar', kekurangan='$kekurangan' where no_nota='$no_nota'");
		if($a){
			mysql_query("insert into history_pembayaran_grosir(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values(now(), '$_POST[ts_dibayar]', '$kekurangan', '$no_nota')");
			$data[] = array('status' => 'berhasil menyimpan tagihan');
		}else{
			$data[] = array('status' => 'gagal menyimpan tagihan');
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='edit tp'){
		$no_nota = escape($_POST['tp_no_nota']);
		$a = mysql_query("select m.*, tgl_bayar, jumlah_cicil, kekurangan_cicil, nama_suplier, no_tlp, datediff(tgl_tempo, curdate()) hit_mundur from barang_masuk m join suplier s on m.kode_suplier=s.kode_suplier left join history_pembayaran_suplier h on m.no_nota=h.no_nota where m.no_nota='$no_nota'");
		$jml = mysql_num_rows($a);
		if($jml==0){
			$data[] = NULL;
		}else{
			while($b = mysql_fetch_array($a)){
				$data[] = $b;
			}
		}
		echo json_encode($data);
	}else if($_POST['aksi_home']=='simpan tp'){
		$no_nota = escape($_POST['tp_no_nota']);
		$sudahdibayar = escape($_POST['tpsudahdibayar']);
		$dibayar = $sudahdibayar+escape($_POST['tp_dibayar']);
		$tgl_tempos = explode("/", escape($_POST['tp_tgl_tempo']));
		$tgl_tempo = $tgl_tempos[2].'-'.$tgl_tempos[1].'-'.$tgl_tempos[0];
		$kekurangan = escape($_POST['tp_kekurangan']);
		if($kekurangan=='0'){
			$tgl_tempo = 'null';
		}else{
			$tgl_tempo = "'$tgl_tempo'";
		}
		$a = mysql_query("update barang_masuk set tgl_tempo=$tgl_tempo, dibayar='$dibayar', kekurangan='$kekurangan' where no_nota='$no_nota'");
		if($a){
			mysql_query("insert into history_pembayaran_suplier(tgl_bayar, jumlah_cicil, kekurangan_cicil, no_nota) values(now(), '$_POST[tp_dibayar]', '$kekurangan', '$no_nota')");
			$data[] = array('status' => 'berhasil menyimpan tagihan');
		}else{
			$data[] = array('status' => 'gagal menyimpan tagihan');
		}
		echo json_encode($data);
	}
}
?>