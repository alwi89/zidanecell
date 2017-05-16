<?php
session_start();
require_once("koneksi.php");
$id_cabang = $_SESSION['cbg'];
$query = mysql_query("select * from sales where id_cabang='$id_cabang' order by kode_sales asc");
$jml = mysql_num_rows($query);
//$data = array();
while($qHasil = mysql_fetch_array($query)){
  $data[] = 
    array("KODE SALES" => $qHasil['kode_sales'], "NAMA SALES" => trim($qHasil['nama_sales']), 'ALAMAT' => $qHasil['alamat'], 'NO TELP' => $qHasil['no_tlp'], 'pin_bb' => $qHasil['pin_bb']);
}
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "master_sales_".date('Y-m-d').".xls";

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