<?php
session_start();
$id_cabang = $_SESSION['cbg'];
require_once("koneksi.php");
$cabang = urldecode($_GET['cabang']);
if($cabang=='SEMUA'){
	$nama_cabang = 'SEMUA';
	$query = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where kekurangan='0' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, jumlah_cicil total, 0 modal, 0 laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where tgl_bayar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
");
}else{
	$qNama = mysql_query("select nama_cabang, jenis from cabang where kode_cabang='$cabang'");
	$hNama = mysql_fetch_array($qNama);
	$nama_cabang = $hNama['nama_cabang'].' - '.$hNama['jenis'];
	$query = mysql_query("select 'ecer' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty) laba from ecer t join ecer_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
union all
select 'grosir' jenis, nama_cabang, sum(harga*qty) total, sum(harga_modal*qty) modal, sum(harga*qty)-sum(harga_modal*qty)-sum(potongan) laba from grosir t join grosir_detail d on t.no_nota=d.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and  kekurangan='0' and tgl_keluar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
union all
select 'pembayaran tempo' jenis, nama_cabang, jumlah_cicil total, 0 modal, 0 laba from history_pembayaran_grosir h join grosir t on h.no_nota=t.no_nota join cabang c on t.id_cabang=c.kode_cabang where t.id_cabang='$cabang' and tgl_bayar between '$_GET[dari]' and '$_GET[sampai]' group by t.id_cabang
");
}
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("JENIS TRANSAKSI" => $qHasil['jenis'], "NAMA CABANG" => $qHasil['nama_cabang'], "TOTAL TRANSAKSI" => toIdr($qHasil['total']), 'MODAL' => toIdr($qHasil['modal']), 'LABA' => toIdr($qHasil['laba']));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_utama_$nama_cabang"."_$_GET[dari]_sd_$_GET[sampai]".".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;

?>