<?php
require_once("koneksi.php");
/*
$_POST['aksi']='data';
$_POST['dari']='01/04/2016';
$_POST['sampai']='30/04/2016';
*/
if(isset($_POST['aksi'])){
	if($_POST['aksi']=='data'){
		$daris = explode("/", escape($_POST['dari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['sampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$cabang = $_POST['cabang'];
		if($cabang=='SEMUA'){
			$a = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where kekurangan='0' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, sum(jumlah_cicil) total, '0' modal, '0' laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_bayar between '$dari' and '$sampai' group by t.id_cabang
");
		}else{
			$a = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and kekurangan='0' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, sum(jumlah_cicil) total, '0' modal, '0' laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_bayar between '$dari' and '$sampai' group by t.id_cabang
");
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
	}else if($_POST['aksi']=='Cetak'){
		$daris = explode("/", escape($_POST['utamadari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['utamasampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];	
		$cabang = $_POST['laputamacabang'];
		if($cabang=='SEMUA'){
			$nama_cabang = 'SEMUA';
			$a = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where kekurangan='0' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, sum(jumlah_cicil) total, '0' modal, '0' laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_bayar between '$dari' and '$sampai' group by t.id_cabang
");
		}else{
			$qNama = mysql_query("select nama_cabang, jenis from cabang where kode_cabang='$cabang'");
			$hNama = mysql_fetch_array($qNama);
			$nama_cabang = $hNama['nama_cabang'].' - '.$hNama['jenis'];
			$a = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and kekurangan='0' and tgl_keluar between '$dari' and '$sampai' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, sum(jumlah_cicil) total, '0' modal, '0' laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_bayar between '$dari' and '$sampai' group by t.id_cabang
");
			
		}
		$jml = mysql_num_rows($a);
		if($jml==0){
			echo '<script>alert("data kosong");javascript:history.go(-1);</script>';
		}else{
			require_once('lap_utama_cetak.php');
		}
	}else if($_POST['aksi']=='Export'){
		$daris = explode("/", escape($_POST['utamadari']));
		$dari = $daris[2].'-'.$daris[1].'-'.$daris[0];
		$sampais = explode("/", escape($_POST['utamasampai']));
		$sampai = $sampais[2].'-'.$sampais[1].'-'.$sampais[0];
		$cabang = urlencode($_POST['laputamacabang']);
		header("location:lap_utama_excel.php?dari=$dari&sampai=$sampai&cabang=$cabang");
	}else if($_POST['aksi']=='data cabang'){
		$a = mysql_query("select * from cabang");
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