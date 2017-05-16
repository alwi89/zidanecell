<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
$query = mysql_query("select no_nota, nama_sales, no_tlp, pin_bb, total, dibayar, kekurangan, tgl_tempo from grosir g join sales s on g.kode_sales=s.kode_sales where kekurangan<>'0' and g.id_cabang='$id_cabang' and tgl_tempo between '$_GET[dari]' and '$_GET[sampai]'");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("TGL TEMPO" => $qHasil['tgl_tempo'], "NO NOTA" => $qHasil['no_nota'], "SALES" => $qHasil['nama_sales'],  'HP' => $qHasil['no_tlp'], 'PIN BB' => $qHasil['pin_bb'], "TOTAL" => toIdr($qHasil['total']),'DIBAYAR' => toIdr($qHasil['dibayar']), 'KEKURANGAN' => toIdr($qHasil['kekurangan']));
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "laporan_tagihan_sales_$_GET[dari]_sd_$_GET[sampai]".".xls";

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