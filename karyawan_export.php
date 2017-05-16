<?php
require_once("koneksi.php");
$query = mysql_query("select k.*, nama_cabang from karyawan k join cabang c on k.id_cabang=c.kode_cabang");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("USERNAME" => $qHasil['username'], "NAMA KARYAWAN" => trim($qHasil['nama']), "CABANG" => trim($qHasil['nama_cabang']), 'NO TELP' => $qHasil['no_telp'], 'PIN BB' => $qHasil['pin_bb'], 'PASSWORD' => $qHasil['password'], 'LEVEL' => $qHasil['level'], 'STATUS' => $qHasil['status']);
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "master_karyawan_".date('Y-m-d').".xls";

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