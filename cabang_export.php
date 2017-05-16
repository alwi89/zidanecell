<?php
require_once("koneksi.php");
$query = mysql_query("select * from cabang order by kode_cabang asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("KODE CABANG" => $qHasil['kode_cabang'], "NAMA CABANG" => $qHasil['nama_cabang'], 'ALAMAT' => $qHasil['alamat'], 'OWNER' => $qHasil['owner'], 'JENIS' => $qHasil['jenis']);
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "master_cabang_".date('Y-m-d').".xls";

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